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
        <title>Guesser</title>
    </head>
    <body>
        <h1 style="text-align: center;">Random number generator</h1>

        <span style="display: inline-block; width: 300px; height: 50px;"></span>

        <h3 style="text-align: center;">Number you choose will be the highest number, generator can generate</h3>

        <form style="text-align: center;" method="get">
            <input type="text" name="num" placeholder="Enter an ending number">
            <button type="submit">Submit</button>
        </form>

        <span style="display: inline-block; width: 300px; height: 50px;"></span>

        <div class="result" style="text-align: center; color: red; font-size: 50px;">
            <?php echo $next; ?>
        </div>

        <span style="display: inline-block; width: 300px; height: 100px;"></span>

        <div style="text-align: center;">
            <img src="target.png" style="width: 400px; height: 300px; ">
        </div>

    </body>
</html>
