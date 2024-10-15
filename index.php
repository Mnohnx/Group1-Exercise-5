<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group 1 Form Validation</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="main.css">
    
    
   
</head>
<body>
    <div>
        <h2>Form Validation</h2>
        <form id="myForm">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name">

            <label for="email">Email:</label>
            <input type="text" id="email" name="email">

            <button type="submit">Submit</button>
        </form>

        <div id="result"></div>
    </div>

    <script>
        $(document).ready(function(){
            $("#myForm").on("submit", function(e){
                e.preventDefault(); // Prevent form submission

                var name = $("#name").val().trim();
                var email = $("#email").val().trim();
                var error = false;

                // Clear previous result messages
                $("#result").removeClass("error success").html("");

                // Client-side validation
                if (name === "") {
                    $("#result").addClass("error").html("Name is required.");
                    error = true;
                } else if (email === "") {
                    $("#result").addClass("error").html("Email is required.");
                    error = true;
                } else if (!validateEmail(email)) {
                    $("#result").addClass("error").html("Invalid email format.");
                    error = true;
                }

                // If no client-side errors, proceed with AJAX request
                if (!error) {
                    $.ajax({
                        url: "index.php", // Assuming form is submitted to the same file
                        type: "POST",
                        data: {name: name, email: email},
                        success: function(response){
                            if(response.includes("Validation successful")) {
                                $("#result").addClass("success").html(response);
                            } else {
                                $("#result").addClass("error").html(response);
                            }
                        }
                    });
                }
            });

            // Email validation function
            function validateEmail(email) {
                var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));

        // Simple validation checks
        if (empty($name)) {
            echo "Name is required.";
            exit;
        }

        if (empty($email)) {
            echo "Email is required.";
            exit;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
            exit;
        }

        // If everything is okay
        echo "Validation successful. Name: " . $name . ", Email: " . $email;
    }
    ?>
</body>
</html>
