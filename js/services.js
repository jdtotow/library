var _data_books = null;
var _data_members = null;
var _borrow = null;
var _addCopy = null;
var _no_copy = false;
var _isbn = null;
var _data_borrow = null;

$(document).ready(function(){
    getBooks();
    getMembers();
    getBorrowedBooks();
    function getBooks(){
        sendRequest('request=get_books',handleBooks);
    }
    function handleBooks(data){
        try{
            var json = JSON.parse(data);
            _data_books = json;
            showBooks(json);
        }
        catch(e){
            console.log(e);
        }
    }
    function showBooks(data){
        var content = "";
        var content_inspect = "";
        for(var i=0;i<data.length;i++){
            var isbn = data[i].isbn;
            var li = '<li class="list-group-item">'+data[i].name+'<div class="btn-group btn-group-xs pull-right" role="group" aria-label="..."><button type="button" class="btn btn-default btn-info"><span class="badge">'+data[i].copy+'</span> '+'</button><button type="button" isbn="'+isbn+'" copy="'+data[i].copy+'" onclick="addCopy(this);" class="btn btn-default btn-info">Add copy</button><button type="button" isbn="'+isbn+'" copy="'+data[i].copy+'" onclick="borrow(this);" class="btn btn-default btn-primary">Borrow</button></div>'+'</li>';
            content +=li;
            var idcollapse = "col_book"+i;
            content_inspect += '<button class="btn btn-primary" data-toggle="collapse" data-target="#'+idcollapse+'">'+data[i].name+'</button><div id="'+idcollapse+'" class="collapse">Madeblo</div><br/><br/>';
        }
        $('#book_container').html(content);
        $('#inspect_book_container').html(content_inspect);
    }
    function getMembers(){
        sendRequest("request=get_members",handleMembers);
    }
    function handleMembers(data){
        try{
            var json = JSON.parse(data);
            _data_members = json;
            showMembers(json);
        }
        catch(e){
            console.log(e);
        }
    }
    function showMembers(data){
        var content = "";
        var content_inspect = ""
        for(var i=0;i<data.length;i++){
            var li = '<li class="list-group-item">'+data[i].name+'</li>';
            content +=li;
            var idcollapse = "col_member"+i;
            content_inspect += '<button class="btn btn-primary" data-toggle="collapse" data-target="#'+idcollapse+'">'+data[i].name+'</button><div id="'+idcollapse+'" class="collapse">Madeblo</div><br/><br/>';
        }
        $('#member_container').html(content);
        $('#inspect_member_container').html(content_inspect);
    }
    function getBorrowedBooks(){
        sendRequest("request=get_borrow",handleBorrow);
    }
    function handleBorrow(data){
        try{
            var json = JSON.parse(data);
            _data_borrow = json;
            showBorrow(json);
        }
        catch(e){
            console.log(e);
        }
    }
    function showBorrow(data){
        var content = "";
        var notify_content = "";
        for(var i=0;i<data.length;i++){
            var state = data[i].state;
            var date = new Date();
            date.setTime(parseInt(data[i].date));
            var now = new Date().getTime();
            var expired = (parseInt(data[i].date) - now > 0) ? "btn-info" : "btn-danger";
            var li = '<li class="list-group-item">'+data[i].title+' ('+data[i].name+') '+'<div class="btn-group btn-group-xs pull-right" role="group" aria-label="..."><button type="button" class="btn btn-default '+expired+' "><span class="badge">'+date.toDateString()+'</span> '+'</button><button type="button" id="'+data[i].id+'" onclick="returnBook(this);" class="btn btn-default btn-primary">Return book</button></div>'+'</li>';
            content +=li;
            if(parseInt(data[i].date) - now > 0){
                var email = data[i].email;
                notify_content += '<li class="list-group-item">'+data[i].title+' ('+data[i].name+') '+'<div class="btn-group btn-group-xs pull-right" role="group" aria-label="..."><button type="button" class="btn btn-default btn-danger "><span class="badge">'+date.toDateString()+'</span> '+'</button><button type="button" isbn="'+data[i].isbn+'" email="'+email+'" title="'+data[i].title+'" onclick="sendMail(this);" class="btn btn-default btn-primary">Notify</button></div>'+'</li>';
            }
        }
        $('#borrow_container').html(content);
        $('#notify_container').html(notify_content);
    }
    $('#btn_login_admin').click(function(){
    	var username = $('#username').val();
    	var password = $('#password').val();
    	if(username === "" || password===""){
    		showMessage("All field are required, please them","#msg_or_error","error");
    		return false;
    	}
    	var data = "request=login&username="+username+"&password="+password;
    	sendRequest(data,handleLogin);
    });
    function handleLogin(data){
    	if(data==="OK"){
    		window.location.href = 'management.php';
    	}
    	else{
    		showMessage(data,"#msg_or_error","error");
    	}
    }
    $('#btn_new_book').click(function(){
        var request = "request=get_list_fields";
        sendRequest(request,handleListFields);
    });
    $('#btn_new_member').click(function(){
        $('#newmember').modal('show');
    });
    $('#btn_new_borrow').click(function(){
        $('#newborrow').modal('show');
    });
    $('#btn_create_member').click(function(){
        var name = $('#member_name').val();
        var code =$('#code').val();
        var email = $('#email').val();
        if(name==="" || code==="" || email===""){
            showMessage("Please fill the name","#msg_or_error_new_member","error");
            return false;
        }
        var data = "request=add_member&name="+name+"&code="+code+"&email="+email;
        sendRequest(data,handleAddMember);
    });
    function handleAddMember(data){
        if(data==="OK"){
            getMembers();
            $('#member_name').val("");
            showMessage("Member added successfully","#msg_or_error_new_member","success");
        }
        else{
            showMessage(data,"#msg_or_error_new_member","error");
        }
    }
    function handleListFields(data){
        try{
            var data = JSON.parse(data);
            setDataToSelect(data,'select_fields');
            $('#newbook').modal('show');
        }
        catch(e){
            console.log(e);
        }
    }
    function generateIsbn(){
        //the format is 2-86889-006-7
        var code = String(getRandomInRange(1,9));
        code +="-";
        code += String(getRandomInRange(10000,99999));
        code +="-";
        code += String(getRandomInRange(100,999));
        code +="-";
        code += String(getRandomInRange(1,9));


        return code;
    }
    function getRandomInRange(min, max) {
        return Math.floor(Math.random() * (max - min) + min);
    }
    $('#btn_generate_isbn').click(function(){
        var isbn = generateIsbn();
        $('#isbn').val(isbn);
    });
    $('#btn_create_book').click(function(){
        var book_name = $('#book_name').val();
        var author = $('#author').val();
        var isbn = $('#isbn').val();
        if(book_name==="" || author==="" || isbn===""){
            showMessage("Please fill all fields","#msg_or_error_new_book","error");
            return false;
        }
        var n_copy = $('#n_copy').val();
        var field = $('#select_fields').val();

        var data = "request=add_book&name="+book_name+"&author="+author+"&isbn="+isbn+"&copy="+n_copy+"&field="+field;
        sendRequest(data,handleAddBook);

    });
    function handleAddBook(data){
        if(data=="OK"){
            getBooks();
            showMessage("Book added successfully","#msg_or_error_new_book","success");
            $('#book_name').val("");
            $('#author').val("");
            $('#n_copy').val("");
            $('#isbn').val("");
        }
        else{
            showMessage(data,"#msg_or_error_new_book","error");
        }
    }
    function borrow(isbn,copy){
        var title = '';
        for(var i=0;i<_data_books.length;i++){
            if(_data_books[i].isbn===isbn){
                title = _data_books[i].name;
            }
        }

        $('#newborrow').modal('show');
        $('#title_modal_borrow').html(title);
        setDataToSelect(_data_members,"select_member");
        if(parseInt(copy)===0){
            var msg = '<div class="alert alert-warning" role="alert">There is no copy for this book, your order will be added in waiting list</div>';
            var container = $('#msg_or_error_new_borrow');
            container.html(msg);
            container.show();
            container.fadeOut(20000);
            _no_copy = true;
        }
        else{
            _no_copy = false;
        }
        _isbn = isbn;
    }
    _borrow = borrow;
    function addCopy(isbn){
        var title = '';
        for(var i=0;i<_data_books.length;i++){
            if(_data_books[i].isbn===isbn){
                title = _data_books[i].name;
            }
        }

        $('#addcopy').modal('show');
        $('#title_modal_addcopy').html(title);
        _isbn = isbn;
    }
    _addCopy = addCopy;
    $('#btn_create_borrow').click(function(){
        var iduser = $('#select_member').val();
        var state = (_no_copy) ? "waiting" : "borrow";
        var _date = $('#date').val();
        var date = new Date(_date).getTime();
        var data = "request=add_borrow&isbn="+_isbn+"&user="+iduser+"&state="+state+"&date="+date;
        sendRequest(data,handleAddBorrow);
    });
    function handleAddBorrow(data){
        if(data==="OK"){
            var msg = (_no_copy) ? "Your order has been added successfully in queue" : "Borrow operation finished";
            getBooks();
            getBorrowedBooks();
            showMessage(msg,"#msg_or_error_new_borrow","success");
        }
        else{
            showMessage(data,"#msg_or_error_new_borrow","error");
        }
    }
    $('#btn_add_copy').click(function(){
        var n_copy = $('#add_copy_number').val();
        if(n_copy==="" || n_copy===0){
            return null;
        }
        var data = "request=add_copy&isbn="+_isbn+"&copy="+n_copy;
        sendRequest(data,handleAddCopy);
    });
    function handleAddCopy(data){
        if(data==="OK"){
            getBooks();
            showMessage("Copy number updated","#msg_or_error_add_copy","success");
            $('#addcopy').modal('hide');
        }
        else{
            showMessage(data,"#msg_or_error_add_copy","error");
        }

    }
    $('#btn_search').click(function(){
        var _search = $('#search_text').val();
        if(_search===""){
            getBorrowedBooks();
        }
        else{
            var data = "request=search&keyword="+_search;
            sendRequest(data,handleSearch);
        }
    });
    function handleSearch(data){
        try{
            _data_borrow = JSON.parse(data);
            showBorrow(_data_borrow);
        }
        catch(e){
            console.log(e);
        }
    }

	//////////////////////////////////////////////////////
	function sendRequest(data,callback){
		var http = new XMLHttpRequest();
    	http.open("POST", 'services.php', true);
   		//Send the proper header information along with the request
    	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    	http.onreadystatechange = function() {//Call a function when the state changes.
			if(http.readyState == 4 && http.status == 200) {
				callback(http.responseText);
			}
		}
		http.send(data);
	}
	function showMessage(message,name,type){
		var container = $(name);
	    var name_class = (type==="success") ? "alert-success" : "alert-danger";
   	    var content = '<div class="alert '+name_class+'" role="alert">'+message+'</div>';
   		container.html(content);
   		container.show();
   		container.fadeOut(5000);
 	}
    function setDataToSelect(_data,element_name){
        var options = "";                              
        for(var i=0;i<_data.length;i++){
            var text_option = _data[i].name;
            var value_option = _data[i].id;
            options += '<option value="'+value_option+'">'+text_option+'</option>';
        }
        var element = $('#'+element_name);
        element.html(options);
    }
	//////////////////////////////////////////////////////
});
function borrow(element){
    var isbn = element.getAttribute("isbn");
    var copy = element.getAttribute("copy");
    _borrow(isbn,copy);
}
function addCopy(element){
    var isbn = element.getAttribute("isbn");
    _addCopy(isbn);
}
function sendMail(element){
    var email = element.getAttribute("email");
    var isbn = element.getAttribute("isbn");
    var title = element.getAttribute("title");

    window.location.href = "mailto:"+email+"?subject="+title+"("+isbn+")";
}