<?php
// Проверяем наличие параметра id перед дальнейшей обработкой
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    require_once "config.php";
    
    // Подготовка запроса
    $sql = "SELECT * FROM country WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Привязываем переменную к подготовленному запросу
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Устанавливаем параметры
        $param_id = trim($_GET["id"]);
        
        // Попытка выполнить запрос
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Получаем строку как ассоциативный массив. */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Получаем значение каждого поля
                $name = $row["name"];
                $capital = $row["capital"];
                $people = $row["people"];
            } else{
                // URL не содержит идентификатор. Перенаправляем на страницу ошибки
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Что-то пошло не так. Пожалуйста, попробуйте позже.";
        }
    }
     
    mysqli_stmt_close($stmt);
    
    mysqli_close($link);
} else{
    // URL не содержит параметр id. Перенаправляем на страницу ошибки
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Просмотр записи</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Просмотр записи<h1>
                    </div>
                    <div class="form-group">
                        <label>Название страны</label>
                        <p class="form-control-static"><?php echo $row["name"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Столица</label>
                        <p class="form-control-static"><?php echo $row["capital"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Кол-во жителей</label>
                        <p class="form-control-static"><?php echo $row["people"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Назад</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
