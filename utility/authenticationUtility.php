<?php

//Starts the session (it is needed for the login tracking)
session_start();

//Ensures that the user is logged in before accessing protected routes
function requireAuth(): int {
    //If there is not a userId stored  in session then the user is not logged in
    if(!isset($_SESSION["userId"]))  {
        jsonResponse(401, ["messege" => "unauthoried"]);
    }


    //Returns the logged in user's ID
    return (int) $_SESSION["userId"];
}