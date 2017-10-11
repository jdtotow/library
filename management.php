<?php 
	session_start();
	if(isset($_SESSION['user']) && isset($_SESSION['type'])){
		?>
		<!DOCTYPE html>
<html lang="en">
<head>
  <title>:::LBS Management:::</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="js/services.js" type="text/javascript"></script> 
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
    	<a class="navbar-brand navbar-left" href="#"><img alt="logo" class="logo" src="images/logo.png"></a>
    	<p class="navbar-text navbar-left" style="font-weight:bold;color:white">Library Borrow System</p>
    	<button type="button" class="btn navbar-btn pull-right">Sign out</button>
    </div>
  </div>
</nav>
<div class="container">
	<ul class="nav nav-tabs" role="tablist">
   		<li role="presentation" class="active"><a href="#tab_report" role="tab" data-toggle="tab">Notify</a></li>
  		<li role="presentation"><a href="#tab_book_and_borrow" role="tab" data-toggle="tab">Book and borrow management</a></li>
      <li role="presentation"><a href="#tab_inspect" role="tab" data-toggle="tab">Inspect Book and member</a></li>
   </ul>
   <div class="tab-content">
   	<div id="tab_report" role="tabpanel" class="tab-pane fade in active">
   		<div class="row">
   			<div class="col-sm-6">
   			    <h2 style="font-weight: bold">Notifications</h2>
   				<div class="jumbotron">
   					 <ul class="list-group" id="notify_container"></ul>
   				</div>
   			</div>

   		</div>
   	</div>
   	<div id="tab_book_and_borrow" role="tabpanel" class="tab-pane">
   		<div class="row">
   			<div class="col-sm-6">
   				 <div class="page-header"><h3>Books</h3></div>
   				 <div class="row">
   				 	<div class="col-sm-4"><button class="btn btn-primary pull-left" id="btn_new_book">Add book</button></div>
   				 	<div class="col-sm-4"></div>
   				 	<div class="col-sm-4"></div>
   				 </div>
   				 <br/><br/>
   				 <ul class="list-group" id="book_container"></ul>
   			</div>
   			<div class="col-sm-6">
   				 <div class="page-header"><h3>Borrow</h3></div>
   				 <div class="row">
   				    <div class="col-sm-4"></div>
   				 	<div class="col-sm-8">
   				 		<div class="input-group">
      						<input type="text" class="form-control" id="search_text" placeholder="Search">
      							<span class="input-group-btn">
        							<button class="btn btn-default" type="button" id="btn_search">Search</button>
      							</span>
    					</div>
   				 	</div>
   				 </div>
   				 <br/><br/>
   				 <ul class="list-group" id="borrow_container"></ul>
   			</div>
   		</div>
   		<div class="row">
   			<div class="col-sm-6">
   				<div class="page-header"><h3>Members</h3></div>
   				<div class="row">
   				    <div class="col-sm-4"></div>
   				    <div class="col-sm-4"></div>
   					<div class="col-sm-4"><button class="btn btn-primary pull-right" id="btn_new_member">Add Member</button></div>
   				</div>
   				<br/><br/>
   				<ul class="list-group" id="member_container"></ul>
   			</div>
   			<div class="col-sm-6"></div>
   		</div>
   	</div>
    <div id="tab_inspect" role="tabpanel" class="tab-pane">
      <div class="row">
        <div class="col-sm-6">
          <div class="page-header"><h3>Inspect book</h3></div>
          <ul class="list-group" id="inspect_book_container"></ul>
        </div>
        <div class="col-sm-6">
          <div class="page-header"><h3>Inspect member</h3></div>
          <ul class="list-group" id="inspect_member_container"></ul>
        </div>
      </div>
    </div>
   </div>
</div>
<nav class="navbar navbar-inverse navbar-static-bottom" style="margin-bottom:0px;margin-left:0px;margin-right:0px;clear:both;">
  <div class="container">
    <div class="navbar-bottom">
    	<p class="navbar-text navbar-right">Copyright © 2017 Library Borrow System</p>
    </div>
  </div>
</nav>
</body>
<!-- -->
<div class="modal fade" id="newbook">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="title_modal_book">Add book</h4>
      </div>
      <div class="modal-body">
        <div id="msg_or_error_new_book" style="display:none"></div>
             <div class="input-group inputspace">
  					<span class="input-group-addon" >Book field</span>
  					<select id="select_fields" class="form-control" aria-describedby="basic-addon1"></select>
 			 </div>
 			 <div class="input-group inputspace">
  					<span class="input-group-addon">ISBN</span>
  					<input type="text" class="form-control" id="isbn" aria-describedby="basic-addon1">
 			 </div>
 			 <div class="input-group inputspace">
  					<span class="input-group-addon">Book title</span>
  					<input type="text" class="form-control" id="book_name" aria-describedby="basic-addon1">
 			 </div>
 			 <div class="input-group inputspace">
  					<span class="input-group-addon">Author</span>
  					<input type="text" class="form-control" id="author" aria-describedby="basic-addon1">
 			 </div>
 			 <div class="input-group inputspace">
  					<span class="input-group-addon">Copy</span>
  					<input type="number" class="form-control" id="n_copy" aria-describedby="basic-addon1">
 			 </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn_create_book">Create</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- -->
<!-- -->
<div class="modal fade" id="newmember">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="title_modal_member">Add member</h4>
      </div>
      <div class="modal-body">
        <div id="msg_or_error_new_member" style="display:none"></div>
        <div class="input-group inputspace">
  					<span class="input-group-addon" >Member name</span>
  					<input type="text" class="form-control" id="member_name" aria-describedby="basic-addon1">
 			 </div>
       <div class="input-group inputspace">
            <span class="input-group-addon" >Code</span>
            <input type="text" class="form-control" id="code" aria-describedby="basic-addon1">
       </div>
       <div class="input-group inputspace">
            <span class="input-group-addon" >E-mail</span>
            <input type="text" class="form-control" id="email" aria-describedby="basic-addon1">
       </div>
 			 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn_create_member">Create</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- -->
<!-- -->
<div class="modal fade" id="newborrow">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="title_modal_borrow">Add Borrow</h4>
      </div>
      <div class="modal-body">
        <div id="msg_or_error_new_borrow" style="display:none"></div>
             <div class="input-group inputspace">
  					<span class="input-group-addon" >Select member</span>
  					<select id="select_member" class="form-control" aria-describedby="basic-addon1"></select>
 			 </div>
 			 <div class="input-group inputspace">
  					<span class="input-group-addon">Return date</span>
  					<input type="date" class="form-control" id="date" aria-describedby="basic-addon1">
 			 </div>
 			 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn_create_borrow">Borrow</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- -->
<!-- -->
<div class="modal fade" id="addcopy">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="title_modal_addcopy">Add copy</h4>
      </div>
      <div class="modal-body">
        <div id="msg_or_error_add_copy" style="display:none"></div>
 			 <div class="input-group inputspace">
  					<span class="input-group-addon">Enter number</span>
  					<input type="number" class="form-control" id="add_copy_number" aria-describedby="basic-addon1">
 			 </div>
 			 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn_add_copy">Add copy</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- -->
</html>
		<?php
	}
	else{
		header('Location: index.html');
	}
?>
