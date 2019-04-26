<?php
// Процесс удаления
if(isset($_POST["id"]) && !empty($_POST["id"])){
    require_once "config.php";
    
    // Подготовка запроса на удаление
    $sql = "DELETE FROM country WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Привязываем переменную к запросу
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Устанавливаем параметры
        $param_id = trim($_POST["id"]);
        
        // Попытка выполнить запрос
        if(mysqli_stmt_execute($stmt)){
            // Запись удалена успешно. Переходим на главную страницу.
            header("location: index.php");
            exit();
        } else{
            echo "Что-то пошло не так. Пожалуйста, попробуйте позже.";
        }
    }
     
    mysqli_stmt_close($stmt);
    
    mysqli_close($link);
} else{
    // Проверяем наличие параметра id
    if(empty(trim($_GET["id"]))){
        // URL не содержит параметр id. Перенаправляем на страницу ошибки
        header("location: error.php");
        exit();
    }
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
                        <h1>Удаление записи</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Вы уверены, что хотите удалить эту запись?</p><br>
                            <p>
                                <input type="submit" value="Да" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">Нет</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
