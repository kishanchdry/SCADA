<form method='post' >
    <input type="file" name="path" />
    <input type='submit' name='submit' value='done' />
</form>
<?php 
if (isset($_POST['submit'])) {
    //$path ='../../logs/LogFiles/W3SVC1/u_ex201023.log';
    $path = $_POST['path'];
    if (unlink($path)) {
        echo 'OK!';
    } else {
        echo 'NOT!';
    }
}
?>