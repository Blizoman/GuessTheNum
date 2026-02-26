<?php 
    
    $num = isset($_GET["num"]) ? (int)$_GET["num"] : 0;
    
    if ($num > 0) {
        $next = rand(1, $num);
    } else {
        $next = "Enter positive number!";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Generator</title>

        <style>
            body {font-family: sans-serif; text-align: center; margin-top: 50px;}
            .spacer {margin-bottom: 50px;}
            .result-box {color: red; font-size: 50px; font-weight: bold; min-height: 60px;}
            input { padding: 10px; font-size: 16px
            
            }
            button { padding: 10px 20px; font-size: 16px; cursor: pointer;}
        </style>
    </head>
    <body>
        <h1 style="text-align: center;">Random number generator</h1>

        <h3 style="text-align: center;" style="margin: 100;">Number you choose will be the highest number, generator can generate</h3>

        <form style="text-align: center;" method="get">
            <input type="text" name="num" placeholder="Enter an ending number" autofocus>
            <button type="submit">Submit</button>
        </form>

        <div class="result" style="text-align: center; color: red; font-size: 50px;">
            <?php echo $next; ?>
        </div>

        <div style="text-align: center;">
            <img src="target.png" style="width: 400px; height: 300px; ">
        </div>

    </body>
</html>
