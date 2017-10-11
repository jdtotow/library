<?php
	if(isset($_POST['request']) && $_POST['request'] !== null){
		$bdd = new PDO('mysql:host=localhost;dbname=library;charset=utf8', 'root', 'ulqz51RE');
		$request = $_POST['request'];
		if($request == "login"){
			$username = $_POST['username'];
			$password = hash('tiger192,3', $_POST['password']);

			$results = $bdd->query("SELECT * FROM operator WHERE username='$username'");
            $results->setFetchMode(PDO::FETCH_OBJ);
			$password_in_db = "";
            while( $result = $results->fetch() )
            {
				$password_in_db = $result->password;
			}
			$results->closeCursor(); 
			
			if($password == $password_in_db){
				session_start();
				$_SESSION['user'] = $username;
				$_SESSION['type'] = "user";
				echo "OK";
			}
			else{
				echo "The username or the password is not correct";
			} 
		}
		else if($request=="get_list_fields"){
			$results = $bdd->query("SELECT * FROM fields");
			$results->setFetchMode(PDO::FETCH_OBJ);
			$response = array();
			while($result = $results->fetch()){
				$field = array("id"=>$result->F_id,"name"=>$result->F_name);
				array_push($response, $field);
			}
			echo json_encode($response);

		}
		else if($request=="add_book"){
			$name = $_POST['name'];
			$field = intval($_POST['field']);
			$copy = $_POST['copy'];
			$author = $_POST['author'];
			$isbn = $_POST['isbn'];

			$sql = "INSERT INTO books(B_ISBN,B_title,B_authors) VALUES(:isbn,:title,:author)";
			$stmt = $bdd->prepare($sql);
			$stmt->bindParam(":isbn",$isbn);
			$stmt->bindParam(":title",$name);
			$stmt->bindParam(":author",$author);

			if(!$stmt->execute()){
				echo $stmt->errorInfo()[2];
				return false;
			}
			$sql = "INSERT INTO copies(C_id,B_ISBN,C_number) VALUES(:id,:isbn,:copy)";
			$stmt2 = $bdd->prepare($sql);
			$id = null;
			$stmt2->bindParam(":id",$id);
			$stmt2->bindParam(":isbn",$isbn);
			$stmt2->bindParam(":copy",$copy);

			if($stmt2->execute()){
				echo "OK";
			}
			else{
				echo $stmt2->errorInfo()[2];
			}
		}
		else if($request=="get_books"){
			$results =$bdd->query("SELECT b.B_title, b.B_ISBN as isbn, b.B_title as title,b.B_authors as author,c.C_number as copy FROM  books as b, copies as c WHERE b.B_ISBN=c.B_ISBN");
			$results->setFetchMode(PDO::FETCH_OBJ);
			$response = array();
			while($result = $results->fetch()){
				$name = $result->title." (".$result->author.")";
				$book = array("copy"=>$result->copy,"name"=>$name,'isbn'=>$result->isbn);
				array_push($response, $book);
			}
			echo json_encode($response);
		}
		else if($request=="get_members"){
			$results =$bdd->query("SELECT M_id as id,M_name as name FROM members");
			$results->setFetchMode(PDO::FETCH_OBJ);
			$response = array();
			while($result = $results->fetch()){
				$book = array("id"=>$result->id,"name"=>$result->name,"code"=>$result->code,"email"=>$result->email);
				array_push($response, $book);
			}
			echo json_encode($response);
		}
		else if($request=="add_member"){
			$stmt = $bdd->prepare("INSERT INTO members(M_id,M_name,M_role,M_stats,code,email) VALUES(null,:name,'student',0,:code,:email)");
			$stmt->bindParam(":name",$_POST['name']);
			$stmt->bindParam(":code",$_POST['code']);
			$stmt->bindParam(":email",$_POST['email']);

			if($stmt->execute()){
				echo "OK";
			}
			else{
				echo $stmt->errorInfo()[2];
			}
		}
		else if($request=="add_borrow"){
			$iduser = $_POST['user'];
			$return_date = $_POST['date'];
			$isbn = $_POST['isbn'];
			$state = $_POST['state'];


		    $sql = "INSERT INTO loans(C_Id,M_id,return_date,B_ISBN,state) VALUES(null,:user,:return_date,:isbn,:state)";
			$stmt = $bdd->prepare($sql);
			$stmt->bindParam(":isbn",$isbn);
			$stmt->bindParam(":user",$iduser);
			$stmt->bindParam(":return_date",$return_date);
			$stmt->bindParam(":state",$state);

			if($stmt->execute()){
				if($state=="borrow"){
					desincrement($isbn,$bdd);
				}
			}
			else{
				echo $stmt->errorInfo()[2];
			}
		}
		else if($request=="get_borrow"){
			$results = $bdd->query("SELECT l.C_Id as id, l.return_date as date,l.state as state, b.B_title as title, m.M_name as name,b.B_ISBN as isbn, m.email as email FROM loans as l,books as b, members as m WHERE m.M_id=l.M_id AND l.B_ISBN=b.B_ISBN");
			$results->setFetchMode(PDO::FETCH_OBJ);
			$response = array();
			while($result = $results->fetch()){
				$loan = array("name"=>$result->name,"email"=>$result->email,"id"=>$result->id,"isbn"=>$result->isbn,"state"=>$result->state,"title"=>$result->title,"date"=>$result->date);
				array_push($response,$loan);
			}
			echo json_encode($response);
		}
		else if($request=="add_copy"){
			$isbn = $_POST['isbn'];
			$copy = $_POST['copy'];
			$stmt = $bdd->prepare("UPDATE copies SET C_number=C_number + :copy WHERE B_ISBN=:isbn");
			$stmt->bindParam(":isbn",$isbn);
			$stmt->bindParam(":copy",$copy);
			if($stmt->execute()){
				echo "OK";
			}
			else{
				$stmt->errorInfo()[2];
			}
		}
		else if($request=="search"){
			$search = $_POST['keyword'];
			$results = $bdd->query("SELECT l.C_Id as id, l.return_date as date,l.state as state, b.B_title as title, m.M_name as name FROM loans as l,books as b, members as m WHERE m.M_id=l.M_id AND l.B_ISBN=b.B_ISBN AND (b.B_title LIKE '%$search%' OR m.M_name LIKE '%$search%' OR b.B_ISBN LIKE '%$search%')");
			$results->setFetchMode(PDO::FETCH_OBJ);
			$response = array();
			while($result = $results->fetch()){
				$loan = array("name"=>$result->name,"id"=>$result->id,"state"=>$result->state,"title"=>$result->title,"date"=>$result->date);
				array_push($response,$loan);
			}
			echo json_encode($response);
		}
		else{
			echo "error request";
		}
	}
	else{
		echo "Error";
	}
	function desincrement($isbn,$bdd){
		$stmt = $bdd->prepare("UPDATE copies SET C_number=C_number-1 WHERE B_ISBN=:isbn");
		$stmt->bindParam(":isbn",$isbn);
		if($stmt->execute()){
			echo "OK";
		}
	}
?>