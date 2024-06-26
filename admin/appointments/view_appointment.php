<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT a.*,CONCAT(c.firstname,' ',c.middlename,' ',c.lastname) as fullname, c.contact, c.gender,c.email,c.address FROM `appointment_list` a inner join `client_list` c on a.client_id = c.id where a.id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
}
?>

<div class="content py-5">
    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h4 class="card-title"><b>Booked Appointment Details</b></h4>
            <div class="card-tools">
                <?php if (isset($status) && $status == 4) : ?>
                    <button class="btn btn-info bg-gradient-info btn-flat btn-sm" type="button" id="upload_report"><i class="fa fa-upload"></i> Upload Report</button>
                <?php endif; ?>
                <button class="btn btn-default bg-gradient-navy btn-flat btn-sm" type="button" id="update_status"> Update Status</button>
                <button class="btn btn-primary btn-flat btn-sm" type="button" id="edit_data"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-danger btn-flat btn-sm" type="button" id="delete_data"><i class="fa fa-trash"></i> Delete</button>
                <a class="btn btn-default border btn-flat btn-sm" href="./?page=appointments" id="delete_data"><i class="fa fa-angle-left"></i> Back</a>
            </div>
        </div>
               <div class="card-body">
            <div class="container-fluid" id="outprint">
                <div class="row">
                    <div class="col-2 border bg-gradient-primary text-light">Appointment Code</div>
                    <div class="col-4 border"><?= isset($code) ? $code :"" ?></div>
                    <div class="col-2 border bg-gradient-primary text-light">Schedule</div>
                    <div class="col-4 border"><?= isset($schedule) ? date("M d, Y h:i A", strtotime($schedule)) :"" ?></div>
                    <div class="col-2 border bg-gradient-primary text-light">Patient Name</div>
                    <div class="col-10 border"><?= isset($fullname) ? $fullname :"" ?></div>
                    <div class="col-1 border bg-gradient-primary text-light">Gender</div>
                    <div class="col-3 border"><?= isset($gender) ? $gender :"" ?></div>
                    <div class="col-1 border bg-gradient-primary text-light">Contact #</div>
                    <div class="col-3 border"><?= isset($contact) ? $contact :"" ?></div>
                    <div class="col-1 border bg-gradient-primary text-light">Email</div>
                    <div class="col-3 border"><?= isset($email) ? $email :"" ?></div>
                    <div class="col-2 border bg-gradient-primary text-light">Address</div>
                    <div class="col-10 border"><?= isset($address) ? $address :"" ?></div>
                    <div class="col-2 border bg-gradient-primary text-light">Status</div>
                    <div class="col-4 border ">
                        <?php 
                            switch ($status){
                                case 0:
                                    echo '<span class="">Pending</span>';
                                    break;
                                case 1:
                                    echo '<span class">Approved</span>';
                                    break;
                                case 2:
                                    echo '<span class">Sample Collected</span>';
                                    break;
                                case 3:
                                    echo '<span class="rounde">Delivered to Lab</span>';
                                    break;
                                case 4:
                                    echo '<span class">Done</span>';
                                    break;
                                case 5:
                                    echo '<span class">Cancelled</span>';
                                    break;
                            }
                        ?>
                    </div>
                    <div class="col-2 border bg-gradient-primary text-light">Prescription</div>
                    <div class="col-4 border ">
                        <?php 
                        if(isset($prescription_path)){
                            echo "<a href='".base_url.$prescription_path."' target='_blank' download='".(explode('?',str_replace("uploads/prescriptions/",'',$prescription_path))[0])."'>".(explode('?',str_replace("uploads/prescriptions/",'',$prescription_path))[0])."</a>";
                        }else{
                            echo "N/A";
                        }
                        ?>
                    </div>
                    <?php if(isset($status) && $status == 6): ?>
                    <div class="col-2 border bg-gradient-primary text-light">Uploaded Report</div>
                    <div class="col-10 border ">
                        <?php if(isset($code) && is_file(base_app."uploads/reports/".$code.".pdf")): ?>
                            <a href='<?= base_url."uploads/reports/".$code.".pdf" ?>' target='_blank' download='<?= $code.".pdf" ?>'><?= $code.".pdf" ?></a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                        <div class="col-3 border bg-gradient-primary text-light">Select Test</div>
                     <div class="col-6 border">
            <!-- Create a select dropdown for selecting the test -->
            <select class="form-control" id="selected_test">
                <?php
                // Fetch and display test options from the test_list table
                $test_qry = $conn->query("SELECT * FROM `test_list` ORDER BY name ASC");
                while ($test_row = $test_qry->fetch_assoc()) {
                    echo '<option value="' . $test_row['id'] . '">' . $test_row['name'] . '</option>';
                }
                ?>
            </select>
        </div>

                <hr>
                <fieldset>
                    <legend class="text-muted">Update History</legend>
                    <table class="table table-striped table-bordered">
                        <colgroup>
                            <col width="10%">
                            <col width="20%">
                            <col width="40%">
                            <col width="30%">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-primary text-light">
                                <th class="text-center">#</th>
                                <th>Date</th>
                                <th>Remarks</th>
                                <th>New Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            if (isset($test_ids) && count($test_ids) > 0) :
                                $history = $conn->query("SELECT* FROM `history_list` where appointment_id = '{$id}' order by unix_timestamp(date_created) asc");
                                while ($row = $history->fetch_assoc()) :
                            ?>
                                    <tr>
                                        <td class="py-1 px-2 text-center"><?= $i++; ?></td>
                                        <td class="py-1 px-2"><?= date("M d, Y H:i", strtotime($row['date_created'])) ?></td>
                                        <td class="py-1 px-2"><?= $row['remarks'] ?></td>
                                        <td class="py-1 px-2">
                                            <?php
                                            switch ($row['status']) {
                                                case 0:
                                                    echo '<span class="">Pending</span>';
                                                    break;
                                                case 1:
                                                    echo '<span class">Approved</span>';
                                                    break;
                                                case 2:
                                                    echo '<span class">Sample Collected</span>';
                                                    break;
                                                case 3:
                                                    echo '<span class="rounde">Delivered to Lab</span>';
                                                    break;
                                                case 4:
                                                    echo '<span class">Done</span>';
                                                    break;
                                                case 5:
                                                    echo '<span class">Cancelled</span>';
                                                    break;
                                                case 6:
                                                    echo '<span class">Report Uploaded</span>';
                                                    break;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                            <?php
                                endwhile;
                                if ($history->num_rows <= 0) :
                            ?>
                                    <tr>
                                        <th class="py-1 text-center" colspan="4">No Data</th>
                                    </tr>
                            <?php
                                endif;
                            else :
                            ?>
                                <tr>
                                    <th class="py-1 text-center" colspan="4">No Data</th>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
    </div>
</div>


<script>
    $(function(){
        $('#delete_data').click(function(){
			_conf("Are you sure to delete this Appointment permanently?","delete_appointment",['<?= isset($id) ? $id : '' ?>'])
		})
        $('#edit_data').click(function(){
			uni_modal("Update Appointment Details","appointments/manage_appointment.php?id=<?= isset($id) ? $id : '' ?>",'mid-large')
		})
        $('#update_status').click(function(){
			uni_modal("Update Appointment's Status","appointments/update_status.php?id=<?= isset($id) ? $id : '' ?>",'mid-large')
		})
        $('#upload_report').click(function(){
			uni_modal("Upload Test Result/Report","appointments/upload.php?id=<?= isset($id) ? $id : '' ?>",'mid-large')
		})
    })
    function delete_appointment($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_appointment",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.href="./?page=appointments";
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>