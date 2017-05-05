<html>

	<head>
		

		<!--Import Google Icon Font-->
	    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" type="text/css" rel="stylesheet">
	    <link rel="stylesheet" type="text/css" href="supplement.css">
	    <!--Import materialize.css-->
	    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

	    <!--Let browser know website is optimized for mobile-->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<script type="text/javascript" src="search.js"></script>
		<script type="text/javascript" src="split.js"></script>
		
		<div class="navbar-fixed">   
			<nav>
		    	<div class="nav-wrapper">
		      		<form>
		        		<div class="input-field">
		          		<input id="filter" type="search" placeholder="Search Hashtags" onkeyup="myFunction()" required>
		          		<label class="label-icon" for="filter"><i class="material-icons">search</i></label>
		          		<i class="material-icons">close</i>
		        		</div>
		      		</form>
		    	</div>
			</nav>
		</div>
	</head>
	<body>
    <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="js/materialize.min.js"></script>
      

	<?php
		
	    // pass in some info;
		require("common.php"); 
		
		if(empty($_SESSION['user'])) { 
  
			// If they are not, we redirect them to the login page. 
			$location = "http://" . $_SERVER['HTTP_HOST'] . "Final-Project/login.php";
			echo '<META HTTP-EQUIV="refresh" CONTENT="0;URL='.$location.'">';
			//exit;
         
        	// Remember that this die statement is absolutely critical.  Without it, 
        	// people can view your members-only content without logging in. 
        	die("Redirecting to login.php"); 
    	} 
		
		// To access $_SESSION['user'] values put in an array, show user his username
		$arr = array_values($_SESSION['user']);
		echo "<br>";
		echo "<center>";
		echo "<h3 class='panel-title'>";
		echo "<font size='8' color='black'>Welcome " . $arr[1] . "</font>";
		echo "</h3>";
		echo "</center>";

		echo "<br>";
		


		// open connection
		$connection = mysqli_connect($host, $username, $password) or die ("Unable to connect!");

		// select database
		mysqli_select_db($connection, $dbname) or die ("Unable to select database!");

		// create query
		$query = "SELECT * FROM symbols";
       
		// execute query
		$result = mysqli_query($connection,$query) or die ("Error in query: $query. ".mysqli_error());

		// see if any rows were returned
		if (mysqli_num_rows($result) > 0) {
    		// print them one after another
    		echo "<div class='container'>";
            echo "<div class='panel panel-default'>";
            echo "<div class='panel-heading'><h3 class='panel-title'><font size='4'>New Tweets:</font></h3></div>";
            echo "<table class='highlight' class='responsive-table striped' id='myTable'>";
            echo "<thead>
				      <tr>
				          <th data-field='usernames'>Username</th>
				          <th data-field='tweets'>Tweet</th>
				          <th data-field='hashtags'>Hashtags</th>
				          <th data-field='likes'>Likes</th>
				          <th data-field='likeButtons'></th>
				          <th data-field='delete'>Delete</th>
				      </tr>
				    </thead>";
            echo "<div class='list-group'>";
            echo "<tbody>";
            while($row = mysqli_fetch_row($result)) {
                echo "<tr>";
                echo "<td>".$row[3]."</td>";
                echo "<td>" . $row[1]."</td>";
                $hashtags = explode(" ", $row[2]);
                echo "<td>";
                for ($i = 0; $i < sizeof($hashtags); $i++) {
					echo "<div class='chip'>".$hashtags[$i]."</div>";

				}
                echo "</td>";
                echo "<td>".$row[4]."</td>";
                echo "<td><a class='waves-effect waves-light btn'><i class='material-icons left'>thumb_up</i></a></td>";
                if ($arr[1] == $row[3]) {
                	echo "<td><a class='waves-effect waves-light btn' href=".$_SERVER['PHP_SELF']."?id=".$row[0].">Delete</a></td>";
            	} else {
            		echo "<td><a class='btn disabled'>Delete</a></td>";
            	}
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</div>";
            echo "</table>";
            echo "</div>";
            echo "</div>";

		} else {
			
    		// print status message
    		echo "No rows found!";
		}

		// free result set memory
		mysqli_free_result($connection,$result);

		// set variable values to HTML form inputs
		$name = $arr[1];
		$country = $_POST['country'];
        $animal = $_POST['animal'];
        $search = $_POST['filter'];
        
        // check to see if user has entered anything
        if ($search != "") {
            // build SQL query
            $query = "SELECT * FROM symbols where animal like filter%";
            // run the query
            $result = mysqli_query($connection,$query) or die ("Error in query: $query. " . mysqli_error());
            // refresh the page to show new update
            echo "<meta http-equiv='refresh' content='0'>";
        } else if ($animal != "") {
            // build SQL query
            $query = "INSERT INTO symbols (Name, country, animal) VALUES ('$name', '$country', '$animal')";
            // run the query
            $result = mysqli_query($connection,$query) or die ("Error in query: $query. " . mysqli_error());
            // refresh the page to show new update
            echo "<meta http-equiv='refresh' content='0'>";
        }
		

		// if DELETE pressed, set an id, if id is set then delete it from DB
		if (isset($_GET['id'])) {

            // create query to delete record
            echo $_SERVER['PHP_SELF'];
            $query = "DELETE FROM symbols WHERE id = ".$_GET['id'];

            // run the query
            $result = mysqli_query($connection,$query) or die ("Error in query: $query. " . mysqli_error());
            
            // reset the url to remove id $_GET variable
            $location = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            echo '<META HTTP-EQUIV="refresh" CONTENT="0;URL='.$location.'">';
            exit;
            
        }
		
		// close connection
		mysqli_close($connection);


	?>
   

    <!-- This is the HTML form that appears in the browser -->
   	<br>
   	<div class="form-inline">
	   	<div class="container">
	        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
	        	<label class="label-icon" for="tweet"><i class="material-icons">forum</i></label>
	        	<div class='input-field'>
	            <input id='tweet' class='form-control' type="text" name="country">
	            <label for='tweet'>Your Tweet Here</label>
	            </div>
	            <label class="label-icon" for="hashtag"><i class="material-icons">turned_in_not</i></label>
	            <div class='input-field'>
<!-- 	            <div name='animal' id='hashtag' class='chips'> -->
	            <input id='hashtag' class='form-control' type="text" name="animal">
<!-- 	            </div> -->
				<label for='hashtag'>Your Hashtags Here (Seperate By Space)</label>
	            <button class="btn waves-effect waves-light" type="submit" name="submit">Submit
    <i class="material-icons right">send</i>
  </button>
	            </div>
	        </form>
	    </div>
	</div>
    <br>
    <center>
    	<form action="logout.php" method="post"><button class="btn waves-effect waves-light">Logout</button></form>
    </center>
    
	</body>
	<script>$('.chips').material_chip();</script>
</html>