<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Список дел</title>
</head>
<style>
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }
    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }
    table th {
        background: #eee;
    }
</style>
<body>
<form method="POST">
        <label >Создать таблицу</label>
        <input type="text" name="name_table" placeholder="Введите название таблицы">
        <button type="submit" name="create">создать</button>
    </form>
<?php
if (isset($_POST['create'])) {
    if (empty($_POST['name_table'])) {
        echo "Введите имя таблицы!";
    } else {
        $createTable = "CREATE TABLE ".$_POST['name_table']."(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        firstname VARCHAR(25) NOT NULL,
        lastname VARCHAR(35) NOT NULL,
        email VARCHAR(40),
        reg_date TIMESTAMP
        )";
        $sth = $connect->prepare($createTable);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);    
    }
}

$sql = "SHOW TABLES";
$statement = $connect->prepare($sql);
$statement->execute();
$tables = $statement->fetchAll(PDO::FETCH_NUM);
echo '<ul>';
foreach ($tables as $table) {
    echo '<li><a href="?name_table=' . $table[0] . '">' . $table[0] . '</a></li>';
}
echo '</ul>';
if (!empty($_GET['name_table'])) {
    if (!empty($_GET['delete'])) {
        $q = $connect->prepare('ALTER TABLE '. addslashes($_GET['name_table']) .' DROP COLUMN ' . addslashes($_GET['delete']));
        $q->execute();
        $table_fields = $q->fetchAll();
    }
    if (!empty($_POST['name'])) {
        $query = $connect->prepare('ALTER TABLE '. addslashes($_GET['name_table']) .' CHANGE ' . addslashes($_POST['currentName']) .' '. addslashes($_POST['name']). ' VARCHAR(50)');
        $query->execute();
    }
    if (!empty($_POST['dataType'])) {
        $query = $connect->prepare('ALTER TABLE '. addslashes($_GET['name_table']) .' MODIFY ' . addslashes($_POST['currentName']) .' '. addslashes(strtoupper($_POST['dataType'])). ' NOT NULL');
        $query->execute();
    }
    $q = $connect->prepare("DESCRIBE " . addslashes($_GET['name_table']));
    $q->execute();
    $table_fields = $q->fetchAll();
    echo '<table style="border: 1px;">';
    echo '<thead><th>Поле</th><th>Тип данных</th><th>Удалить поле</th><th>Изменить название</th><th>Изменить тип</th></thead>';
    echo '<tbody>';
    foreach ($table_fields as $table_field) {
        echo '<tr>';
        echo '<td>';
        echo $table_field['Field'];
        echo '</td>';
        echo '<td>';
        echo $table_field['Type'];
        echo '</td>';
        echo '<td>';
        echo '<a href="?name_table=' . addslashes($_GET['name_table']) . '&delete=' . $table_field['Field'] . '">Удалить поле</a>';
        echo '</td>';
        echo '<td>';
        echo '<a href="changename.php?currentName=' . $table_field['Field'] . '&name_table=' . addslashes($_GET['name_table']) . '">Изменить название</a>';
        echo '</td>';
        echo '<td>';
        echo '<a href="changetype.php?currentType=' . $table_field['Type'] . '&name_table=' . addslashes($_GET['name_table']) . '&currentName=' . $table_field['Field'] . '">Изменить тип</a>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
if (!empty($_GET['title'])) {
    $changeTitleQuery = 'ALTER TABLE ' . addslashes($_GET['name_table']) . ' CHANGE title newtitle VARCHAR(50);';
    $changeTitle = $connect->prepare($changeTitleQuery);
    $changeTitle->execute();
    $table_fields = $q->fetchAll();
}
}
?>
</body>
</html>
