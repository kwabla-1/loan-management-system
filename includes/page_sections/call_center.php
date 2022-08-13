<?php 
    echo '
    <div class="tab__content-item " id="customers" style="display:none">
        <div class="customer__container fadIn">
            <div class="table__container">

                <div class="customer__header">
                    <h2>Customers</h2>
                    <a href="#" class="btn btn--primary"  onclick="openBTab(event,\'add_borrower\')">+ Add Borrower</a>
                </div>
                <div class="custom-table">
                    <div class="table-header1">
                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                        <th>Name</th>
                                        <th>Weeks</th>
                                        <th>Loan</th>
                                        <th>Pending</th>
                                        <th>Tel number</th>
                                        <th>Next payment</th>
                                        <th>Track</th>
                                        <th>Ref number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="table-content">
                        <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="allCustomerTable">
                            <tbody>
                                <!-- table content wil be loaded by javascript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="Approval" class="tab__content-item" style="display: none;">
        <div class="aprovalContainer fadIn">
            <div class="seven">
                <h1>Approval Table</h1>
            </div>

            <div class="custom-table">
                <div class="table-header1">
                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable-head">
                        <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Businees</th>
                            <th>location</th>
                            <th>Contact</th>
                            <th>disbursment</th>
                            <th>request Amount</th>
                            <th>recommended</th>
                            
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>

                <div class="table-content">
                    <table cellpadding="0" cellspacing="0" border="0" class="assessmentTable" id="accessmentTable">
                        <tbody>
                            <!-- CONTENT WILL BE LOADED BY javascript -->
                    

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    ';
?>