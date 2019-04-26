<?php
require_once "config.php";
 
// Определяем переменные и инициализируем пустыми значениями
$name = $capital = $people = "";
$name_err = $capital_err = $people_err = "";
 
// Обработка данных формы
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Получаем значение
    $id = $_POST["id"];
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
        // Подготовка запроса на изменение
        $sql = "UPDATE country SET name=?, capital=?, people=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Привязка переменных к запросу
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_capital, $param_people, $param_id);
            
            // Установка параметров
            $param_name = $name;
            $param_capital = $capital;
            $param_people = $people;
            $param_id = $id;
            
            // Попытка выполнения
            if(mysqli_stmt_execute($stmt)){
                // Изменение успешно. Переходим на главную страницу.
                header("location: index.php");
                exit();
            } else{
                echo "Что-то пошло не так. Пожалуйста, попробуйте позже.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
} else{
    // Проверьте наличие параметра id перед дальнейшей обработкой
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Получить параметр URL
        $id =  trim($_GET["id"]);
        
        // Подготовка запроса
        $sql = "SELECT * FROM country WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Привязка переменной в качестве параметра к запросу
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Устанавливаем параметры
            $param_id = $id;
            
            // Попытка выполнения запроса
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
    }  else{
        // URL не содержит идентификатор. Перенаправляем на страницу ошибки
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Изменение записи</title>
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
                        <h2>Изменение записи</h2>
                    </div>
                    <p> Измените значения и нажмите кнопку Изменить, чтобы обновить запись.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Изменить">
                        <a href="index.php" class="btn btn-default">Отмена</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
