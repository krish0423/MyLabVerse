<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Report</title>
    <style>
        .img-thumb-path {
            width: 100px;
            height: 80px;
            object-fit: scale-down;
            object-position: center center;
        }

        .card-body {
            padding: 20px;
        }

        #makeReportForm {
            max-width: 600px;
            margin: auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        #printReportBtn {
            margin-top: 10px;
        }

        #printReportMsg {
            display: none;
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }

        #closeMsgBtn {
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h3 class="card-title">Make Report</h3>
        </div>
        <div class="card-body">
            <form id="makeReportForm">
                <div class="form-group">
                    <label for="clientSelect">Select Client</label>
                    <!-- Dropdown to select clients -->
                    <select class="form-control" id="clientSelect" name="client_id">
                        <option value="">Select Client</option>
                        <?php
                        $qry = $conn->query("SELECT * FROM `client_list` ORDER BY CONCAT(firstname, ' ', middlename, ' ', lastname) ASC");
                        while ($row = $qry->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="testSelect">Select Test</label>
                    <!-- Dropdown to select tests -->
                    <select class="form-control" id="testSelect" name="test_id">
                        <option value="">Select Test</option>
                        <?php
                        $qry = $conn->query("SELECT * FROM `test_list` WHERE delete_flag = 0 ORDER BY `name` ASC");
                        while ($row = $qry->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Container to load the dynamic form -->
                <div id="dynamicFormContainer"></div>

                <!-- Submit button -->
                <button type="button" class="btn btn-success" onclick="submitReport()">Submit Report</button>
            </form>

            <!-- Print Report button -->
            <button id="printReportBtn" class="btn btn-info" onclick="openPdf()">Print Report</button>

            <!-- Print Report message -->
            <div id="printReportMsg">
                <p>Print of report is ready.</p>
                <button id="closeMsgBtn" class="btn btn-success" onclick="closeMessage()">OK</button>
            </div>
        </div>
    </div>

    <script>
        // Function to load dynamic form based on selected test
        function loadDynamicForm(testId) {
            $.ajax({
                url: _base_url_ + "admin/report/dynamic_form_script.php?test_id=" + testId,
                method: "GET",
                dataType: "html",
                success: function (resp) {
                    $('#dynamicFormContainer').html(resp);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        // Function to handle test selection change
        $('#testSelect').change(function () {
            var selectedTestId = $(this).val();
            if (selectedTestId !== "") {
                loadDynamicForm(selectedTestId);
            } else {
                $('#dynamicFormContainer').html(""); // Clear the dynamic form container
            }
        });

        // Function to submit the report
        function submitReport() {
            var formData = $('#makeReportForm').serialize();

            $.ajax({
                url: _base_url_ + "admin/report/submithemo.php",
                method: "POST",
                data: formData,
                dataType: "json",
                success: function (resp) {
                    if (typeof resp === 'object' && resp.hasOwnProperty('status')) {
                        if (resp.status === 'success') {
                            alert_toast("Report submitted successfully.", 'success');

                            // Show the Print Report message
                            $('#printReportMsg').show();
                        } else {
                            alert_toast("An error occurred: " + resp.message, 'error');
                        }
                    } else {
                        alert_toast("An error occurred. Invalid response format.", 'error');
                    }
                },
                error: function (err) {
                    console.log(err);
                    alert_toast("An error occurred.", 'error');
                }
            });
        }

        // Function to open the PDF report
        function openPdf() {
            // Redirect to pdfhemo.php when the "Print Report" button is clicked
            window.location.href = _base_url_ + "admin/report/pdfhemo.php";
        }

        // Function to close the Print Report message
        function closeMessage() {
            $('#printReportMsg').hide();
        }
    </script>

</body>

</html>
