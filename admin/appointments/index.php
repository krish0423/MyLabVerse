<style>
    .img-thumb-path {
        width: 100px;
        height: 80px;
        object-fit: scale-down;
        object-position: center center;
    }
</style>
<div class="card card-outline card-primary rounded-0 shadow">
    <div class="card-header">
        <h3 class="card-title">List of Booked Appointments</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-bordered table-hover table-striped">
                    <colgroup>
                        <col width="5%">
                        <col width="20%">
                        <col width="20%">
                        <col width="40%">
                        <col width="10%">
                        <col width="5%">
                    </colgroup>
                    <thead>
                        <tr class="bg-gradient-primary text-light">
                            <th>#</th>
                            <th>Date Created</th>
                            <th>Code</th>
                            <th>Patient</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $patient_arr = [];
                        $patients = $conn->query("SELECT *,CONCAT(firstname,' ',middlename,' ', lastname) as fullname FROM `client_list` where id in (SELECT client_id FROM `appointment_list`)");
                        if ($patients->num_rows > 0) {
                            $res = $patients->fetch_all(MYSQLI_ASSOC);
                            $patient_arr = array_column($res, 'fullname', 'id');
                        }
                        $qry = $conn->query("SELECT * from `appointment_list` order by unix_timestamp(date_created) desc ");
                        while ($row = $qry->fetch_assoc()) :
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class=""><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                                <td class=""><?= $row['code'] ?></td>
                                <td class=""><p class="m-0 truncate-1"><?= isset($patient_arr[$row['client_id']]) ? $patient_arr[$row['client_id']] : 'N/A' ?></p></td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="./?page=appointments/view_appointment&id=<?= $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#create_new').click(function () {
            uni_modal("Add New Appointment", "appointments/manage_appointment.php", 'mid-large')
        })
        $('.view_data').click(function () {
            uni_modal("Appointment Details", "appointments/view_appointment.php?id=" + $(this).attr('data-id'))
        })
        $('.edit_data').click(function () {
            uni_modal("Update Appointment Details", "appointments/manage_appointment.php?id=" + $(this).attr('data-id'), 'mid-large')
        })
        $('.delete_data').click(function () {
            _conf("Are you sure to delete this Appointment permanently?", "delete_appointment", [$(this).attr('data-id')])
        })
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 4 }
            ],
        });
    })

    function delete_appointment($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_appointment",
            method: "POST",
            data: { id: $id },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>
