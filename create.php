<?php
require_once "config.php";
 
// Определяем переменные и инициализируем пустыми значениями
$name = $capital = $people = "";
$name_err = $capital_err = $people_err = "";
 
// Обработка данных при отправке
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // проверка наименования страны
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Введите название страны.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[а-яА-ЯЁёa-zA-Z0-9\-\s]{3,30}+$/u")))){
        $name_err = "Введите корректное название страны.";
    } else{
        $name = $input_name;
    }
    
    // проверка наименования столицы
    $input_capital = trim($_POST["capital"]);
    if(empty($input_capital)){
        $capital_err = "Введите название столицы.";
    } elseif(!filter_var($input_capital, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[а-яА-ЯЁёa-zA-Z0-9\-\s]{3,30}+$/u")))){
        $capital_err = "Введите корректное название столицы.";
    } else{
        $capital = $input_capital;
    }
    
    // Проверка количества жителей
    $input_people = trim($_POST["people"]);
    if(empty($input_people)){
        $people_err = "Введите количество жителей";     
    } elseif(!ctype_digit($input_people)){
        $people_err = "Введите положительное целое значение.";
    } else{
        $people = $input_people;
    }
    
    // Проверка ошибок ввода перед записью в базу данных
    if(empty($name_err) && empty($capital_err) && empty($people_err)){
        // Подготовка запроса на вставку
        $sql = "INSERT INTO country (name, capital, people) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Привязка переменных к запросу
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_capital, $param_people);
            
            // Установка параметров
            $param_name = $name;
            $param_capital = $capital;
            $param_people = $people;
            
            // Попытка выполнения
            if(mysqli_stmt_execute($stmt)){
                // Добавлено успешно. Переходим на главную страницу.
                header("location: index.php");
                exit();
            } else{
                echo "Что-то пошло не так. Пожалуйста, попробуйте позже.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Создание новой записи</h2>
                    </div>
                    <p>Заполните эту форму и отправьте, чтобы добавить новую страну в базу данных.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Название страны</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($capital_err)) ? 'has-error' : ''; ?>">
                            <label>Столица</label>
                            <input type="text" name="capital" class="form-control" value="<?php echo $capital; ?>">
                            <span class="help-block"><?php echo $capital_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($people_err)) ? 'has-error' : ''; ?>">
                            <label>Кол-во жителей</label>
                            <input type="text" name="people" class="form-control" value="<?php echo $people; ?>">
                            <span class="help-block"><?php echo $people_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Добавить">
                        <a href="index.php" class="btn btn-default">Отмена</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
