<form action="index.php?name_table=<?php echo $_GET['name_table'] ?>" method="POST">
    <input type="hidden" name="currentName" value="<?php echo $_GET['currentName'] ?>">
    Изменить имя на:
    <p><input type="text" name="name" value="" placeholder="<?php echo $_GET['currentName'] ?>" /></p>
    <p><input type="submit" value="Изменить"/></p>
</form>