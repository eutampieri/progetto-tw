<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>C&T Shop -
            <?= $page_title ?>
        </title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" integrity="sha512-GQGU0fMMi238uA+a/bdWJfpUGKUkBdgfFdgBm72SUQ6BeyWjoY/ton0tEjH+OSH9iP4Dfh+7HM0I9f5eR0L/4w==" crossorigin="anonymous" referrerpolicy="no-referrer" />        <link
            crossorigin="anonymous"
            href="https://cdnjs.cloudflare.com/ajax/libs/fork-awesome/1.1.7/css/fork-awesome.min.css"
            integrity="sha512-9QjPqX/aCNwEQDyMqqMluNOSsHxTwOJNO3d4m5aUeNbyOPm8RcBA5hCUhvGmKFtSmQYGajqPopGtD60FWiWUwg=="
            rel="stylesheet"/>
            <?php
            if(isset($head_template)) {
                require_once($head_template);
            }
            ?>
    </head>
    <body>
        <?php
        require_once($body_template);
        ?>
    </body>
</html>
