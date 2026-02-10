 <?php 
        //  for success msg
        if (isset($response['success'])){
            $response['alert_script'] = '
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "'.$response['message'].'",
                        icon:  "success",
                        confirmButtonText: "OK"
                    });
                });
                
            ';
        }
    // for warning msg


    if (isset($response['warning'])){
            echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "<strong>Warning</strong>",
                        
                        text: "'.$response['message'].'",
                        imageUrl: "assets/img/Warning-icon-isolated-on-transparent-background-PNG.png",
                        imageWidth: 300,
                        imageHeight: 300,

                     
                        confirmButtonText: "OK"
                    });
                });
            </script>';
    }
    if (isset($response['message'])){
        $response['alert_script'] = '
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "'.$response['message'].'",
                    icon: "'.($response[''] ? 'success' : 'error').'",
                    confirmButtonText: "OK"
                });
            });
        ';
    }



    if (isset($success_msg)){
        foreach($success_msg as $msg){
            echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        
                        title: "'.$msg.'",
                        icon: "success",
                        confirmButtonText: "OK"
                    });
                });
            </script>';
        }
    }
    
    if (isset($warning_msg)){
        foreach($warning_msg as $msg){
            echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "<strong>Warning</strong>",
                        
                        text: "'.$msg.'",
                        imageUrl: "assets/img/Warning-icon-isolated-on-transparent-background-PNG.png",
                        imageWidth: 150,
                        imageHeight: 150,

                     
                        confirmButtonText: "OK"
                    });
                });
            </script>';
        }
    }
    
    if (isset($info_msg)){
        foreach($info_msg as $msg){
            echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "'.$msg.'",
                        icon: "info",

                    });
                });
            </script>';
        }
    }
    
    if (isset($error_msg)){
        foreach($error_msg as $msg){
            echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        imageUrl: "assets/img/error_404.png",
                        imageWidth: 300,
                        imageHeight: 400,
                        
                        text: "'.$msg.'",
                       
                        confirmButtonText: "OK"
                    });
                });
            </script>';
        }
    }
    



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <script src="jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    <script src="sweetalert2.all.min.js"></script>

</head>
<body>   
</body>
</html>





