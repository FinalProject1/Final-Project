<html>

	<head>
		

		<!--Import Google Icon Font-->
	    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	    <!--Import materialize.css-->
	    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

	    <!--Let browser know website is optimized for mobile-->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<style>
			h3 { 
			    display: block;
			    font-size: 3em;
			    margin-top: 1em;
			    margin-bottom: 1em;
			    margin-left: 0;
			    margin-right: 0;
			    font-weight: bold;
			}
			h4 { 
			    display: block;
			    font-size: 8em;
			    margin-top: 1em;
			    margin-bottom: 1em;
			    margin-left: 0;
			    margin-right: 0;
			    font-weight: bold;
			}
		</style>

		<script>
			function myFunction() {
			  // Declare variables 
			  var input, filter, table, tr, td, i;
			  input = document.getElementById("filter");
			  filter = input.value.toUpperCase();
			  table = document.getElementById("myTable");
			  tr = table.getElementsByTagName("tr");

			  // Loop through all table rows, and hide those who don't match the search query
			  for (i = 0; i < tr.length; i++) {
			    td = tr[i].getElementsByTagName("td")[2];
			    if (td) {
			      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
			        tr[i].style.display = "";
			      } else {
			        tr[i].style.display = "none";
			      }
			    } 
			  }
			}
		</script>
		   
			<nav class="nav-extended">
			    <div class="nav-wrapper">
					
			    	<a href="#" class="brand-logo">Logo</a>
			    	<ul id="nav-mobile" class="right hide-on-med-and-down">
			        	<li><a href="sass.html">Sass</a></li>
			        	<li><a href="badges.html">Components</a></li>
			        	<li><a href="collapsible.html">JavaScript</a></li>
			    	</ul>
				</div>
				<div class="nav-wrapper">
      				<form>
        				<div class="input-field">
          					<input id="search" type="search" required>
          					<label class="label-icon" for="search"><i class="material-icons">search</i></label>
          					<i class="material-icons">close</i>
        				</div>
      				</form>
    			</div>
			</nav>
	</head>
	<body>
    <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="js/materialize.min.js"></script>
     
    
      <div class="input-field col s6">
          <input id="filter" type="text" class="validate">
          <label for="filter">#Search</label>
      </div>

	<?php
	
	    // pass in some info;
		require("common.php"); 
		
		if(empty($_SESSION['user'])) { 
  
			// If they are not, we redirect them to the login page. 
			$location = "http://" . $_SERVER['HTTP_HOST'] . "/login.php";
			echo '<META HTTP-EQUIV="refresh" CONTENT="0;URL='.$location.'">';
			//exit;
         
        	// Remember that this die statement is absolutely critical.  Without it, 
        	// people can view your members-only content without logging in. 
        	die("Redirecting to login.php"); 
    	} 
		
		// To access $_SESSION['user'] values put in an array, show user his username
		$arr = array_values($_SESSION['user']);
		echo "<br><br><br>";
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
            echo "<table class='table table-hover' id='myTable'>";
            echo "<div class='list-group'>";
            while($row = mysqli_fetch_row($result)) {
                echo "<tr>";
                echo "<td>".$row[3]."</td>";
                echo "<td>" . $row[1]."</td>";
                echo "<td>".$row[2]."</td>";
                echo "<td><a class='btn btn-danger' href=".$_SERVER['PHP_SELF']."?id=".$row[0].">Delete</a></td>";
                echo "</tr>";
            }
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
	            <input class='form-control' placeholder='Your Tweet Here' type="text" name="country">
	            <input class='form-control' placeholder='Your Hashtags Here' type="text" name="animal">
	            <input class='btn btn-success' type="submit" name="submit">
	        </form>
	    </div>
	</div>
    <br>
    <center>
    	<form action="logout.php" method="post"><button class="btn btn-danger">Logout</button></form>
    </center>
    
	</body>
</html>