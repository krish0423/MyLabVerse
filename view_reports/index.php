<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Reports</title>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <!-- Include Bootstrap CSS and JS (if you're using Bootstrap) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4>Uploaded Reports</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="reportTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Test Name</th>
                            <th>Date Created</th>
                            <th>Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once('C:\xampp\htdocs\odlms\view_reports\function.php');
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM `upload_reports` WHERE client_id = '{$_settings->userdata('id')}' ORDER BY created_at DESC");
                        while ($row = $qry->fetch_assoc()):
                            // Fetch client and test names based on IDs
                            $clientName = get_client_name_by_id($row['client_id']);
                            $testName = get_test_name_by_id($row['test_id']);
                        ?>
                            <tr>
                                <td class="text-center"><?= $i++; ?></td>
                                <td class=""><?= $clientName ?></td>
                                <td class=""><p class="m-0 truncate-1"><?= $testName ?></p></td>
                                <td class=""><?= $row['created_at'] ?></td>
                                <td class="">
                                    <button class="btn btn-primary view-report-btn" data-pdf-content="<?= base64_encode($row['pdf_content']) ?>">View Report</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal to display the report content -->
<div class="modal fade" id="viewReportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <embed id="pdfViewer" type="application/pdf" width="100%" height="600px" />
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#reportTable').DataTable({
            columnDefs: [
                { orderable: false, targets: 4 }
            ],
        });

        // Bind click event for the "View Report" button
        $('.view-report-btn').on('click', function () {
            const pdfContent = $(this).data('pdf-content');
            $('#pdfViewer').attr('src', 'data:application/pdf;base64,' + pdfContent);
            $('#viewReportModal').modal('show');
        });
    });
</script>

</body>
</html>
