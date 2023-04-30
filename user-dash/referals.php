<?php
require_once('app.php');
include('header.php');
?>
<style>

</style>

<div class="content-inner w-100">
     <!-- Page Header-->
     <header class="bg-white shadow-sm px-4 py-3 z-index-20">
          <div class="container-fluid px-0">
               <h2 class="mb-0 p-1">Refer users to Equitrim Capital community</h2>
          </div>
     </header>
     <!-- Breadcrumb-->
     <div class="bg-white">
          <div class="container-fluid">
               <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 py-3">
                         <li class="breadcrumb-item"><a class="fw-light" href="index.php">Home</a></li>
                         <li class="breadcrumb-item active fw-light" aria-current="page">Refer users to Equitrim Capital community</li>
                    </ol>
               </nav>
          </div>
     </div>
     <!-- Forms Section-->
     <section class="forms">
          <div class="container-fluid">
               <div class="row">

                    <!-- Basic Form-->
                    <div class="col-md-12 text-center">
                         <strong>You can refer users by sharing your referral link:</strong><br>
                         <div class="mb-3 input-group">
                              <input type="text" class="form-control myInput readonly text-dark bg-light" value="http://localhost/chip/signup.php?ref=<?= $username ?>" id="myInput" readonly="">
                              <div class="input-group-append">
                                   <button class="btn btn-outline-secondary" onclick="myFunction()" type="button" id="button-addon2"><i class="fas fa-copy"></i></button>
                              </div>
                         </div><br>
                         <strong>Your Referral ID</strong><br>
                         <h4 style="color:green;"><?= $username ?></h4> <br>
                         <h3 class="title1">
                              <small>You were referred by</small><br>
                              <i class="fa fa-user fa-2x"></i><br>
                              <small><?= $ref ?></small>
                         </h3>
                    </div>
                    <div class="mt-4 col-md-12">
                         <h2 class="title1 text-dark text-left">Your Referrals.</h2>
                         <div class="table-responsive">
                              <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                   <!-- <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                             <div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select custom-select-sm form-control form-control-sm bg-light text-dark">
                                                            <option value="10">10</option>
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                       </select> entries</label></div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                             <div id="DataTables_Table_0_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm bg-light text-dark" placeholder="" aria-controls="DataTables_Table_0"></label></div>
                                        </div>
                                   </div> -->
                                   <div class="row">
                                        <div class="col-sm-12">
                                             <table class="table UserTable table-hover text-dark dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                                  <thead>
                                                       <tr role="row">
                                                            <th class="sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 174.359px;" aria-sort="descending" aria-label="Client name: activate to sort column ascending">Client name</th>
                                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 112.297px;" aria-label="Parent: activate to sort column ascending">Email</th>
                                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 182.438px;" aria-label="Client status: activate to sort column ascending">Client status</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       <?php
                                                       $results = $db->SelectAll("SELECT * FROM users WHERE referral = :referral", ['referral' => $username]);
                                                       if ($results && count($results)) {
                                                            foreach ($results as $i => $result) {
                                                       ?>
                                                                 <tr>
                                                                      <td><?php echo $result['fullName']; ?></td>
                                                                      <td><?php echo $result['email']; ?></td>
                                                                      <td><?php ($result['is_activated'] == 'yes' ? print('Active') : print('Inactive')) ?></td>
                                                                 </tr>
                                                            <?php
                                                            }
                                                       } else {
                                                            ?>
                                                            <tr class="odd">
                                                                 <td valign="top" colspan="3" class="dataTables_empty text-center">No data available in table</td>
                                                            </tr>
                                                       <?php }; ?>
                                                  </tbody>
                                             </table>
                                        </div>
                                   </div>
                                   <!-- <div class="row">
                                        <div class="col-sm-12 col-md-7">
                                             <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                                  <ul class="pagination">
                                                       <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li>
                                                       <li class="paginate_button page-item next disabled" id="DataTables_Table_0_next"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link">Next</a></li>
                                                  </ul>
                                             </div>
                                        </div>
                                   </div> -->
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </section>
     <!-- Page Footer-->
     <?php require 'footer.php'; ?>
</div>
</div>
</div>