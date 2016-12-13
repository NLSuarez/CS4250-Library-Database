<?php

function invalidRedirect() {
  //If the data was valid, the next section will not be reached.
  //The field for href will need to be changed to whatever href is hosted.
  echo "<html>
  <head>
  </head>
  <body onload=\"if (typeof(Redirect)!=undefined)
setTimeout(function()
    {window.location.href='./index.html'},5000);\">
  <p>
  This query box is only meant to be used for 'Select' statements.
  You will be redirected back to the main page in 5 seconds to try again.
  </p>
  </body>
  </html>";
}

//Begin security
//Needed words to include: "CREATE ","ALTER ", "DROP ", "RENAME ", "TRUNCATE "
//, "INSERT ", "LOAD ", "CALL ", "DELETE ", "REPLACE ", "UPDATE ", "HANDLER ",
// "GRANT ", "REVOKE ", "PURGE ", "RESET ", "SET "
function validateInput($queryInput) {
  $dataValid = true;
  $questionableInput = strtoupper($queryInput);

  if(strpos($questionableInput, "CREATE ") !== false){
    //strpos either returns false if a string is not found within another
    //string. Anything else means that the string exists.
    $dataValid = false;
  } elseif (strpos($questionableInput, "GRANT ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "ALTER ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "DROP ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "RENAME ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "TRUNCATE ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "INSERT ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "LOAD ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "CALL ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "DELETE ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "REPLACE ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "UPDATE ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "HANDLER ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "REVOKE ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "PURGE ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "RESET ") !== false) {
    $dataValid = false;
  } elseif (strpos($questionableInput, "SET ") !== false) {
    $dataValid = false;
  } else {
    //If you pass all of these checks, NOW you can do a query.
    $dataValid = true;
  }

  if($dataValid === true) {
    return true;
  } else {
    return false;
  }
  //End security
}

function main () {
  //Include username and password
  include 'info.php';

  // Create connection
  $Library = new mysqli("localhost", $username, $password, "ssuarez");
  // Check connection
  if ($Library->connect_errno) {
    echo "Failed to connect to MySQL: (" . $Library->connect_errno . ") " . $Library->connect_error;
    exit();
  }
  //Make sure the data is secure.
  $querySafe = validateInput($_POST["query"]);
  if($querySafe) {
    //Generate initial html with a repeat of the query at the top.
    echo '<html>
    <head>
    </head>
    <body>
    <p>'
    . $_POST["query"] . '</p>';
    //Run the query.
    $result = mysqli_query($Library, $_POST["query"]);
    //Check the success of the query.
    if(!$result) {
      //If the query fails for some reason, return an error message.
      printf("Query Error: %s \n", mysqli_error($Library));
    } else {
      //If it didn't, begin printing the table.
      echo '<table>
      <tr>';
      $fieldinfo = mysqli_fetch_fields($result);
      foreach ($fieldinfo as $val) {
        echo '<td>' . $val->name . '</td>';
      }
      echo '</tr>';
      while ($row = mysqli_fetch_row($result)) {
        echo '<tr>';
        for ($i=0; $i < count($row); $i++) {
          echo '<td>' . $row[$i] . '</td>';
        }
        echo '</tr>';
      }
      echo '</table>
      </body>
      </html>';
    }
    mysqli_free_result($result);
  } else {
    invalidRedirect();
  }
}

main();
?>
