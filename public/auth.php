<!DOCTYPE html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta charset="utf-8" />
  <title>Login / Sign Up Form</title>
  <link rel="stylesheet" href="css/signIn.css" />
</head>

<body>
  <div class="container">
  <div id = "messages_div">
        <?php
            if(session_status() != 2) session_start();
            if(isset($_SESSION['error_message'])){
                echo "<p class=\"error_message\">" . $_SESSION["error_message"] . "</p>";
                unset($_SESSION["error_message"]);
            }

            if(isset($_SESSION['success_message'])){
                echo "<p class=\"success_message\">" . $_SESSION["success_message"] . "</p>";
                unset($_SESSION["success_message"]);
            }

            if(isset($_REQUEST['next'])){
                $controller_url = "auth_form_controller.php?next=" . $_REQUEST['next'];
            }
            else{
                $controller_url = "auth_form_controller.php";
            }
        ?>
    </div>

    <form class="form" id="login" method="POST" action=<?php echo $controller_url ?>>
      <h1 class="form__title">Login</h1>
      <div class="form__message form__message--error"></div>
      <div class="form__input-group">
        <input
          type="text"
          class="form__input"
          autofocus
          placeholder="Username"
          name="username"
          required
        />
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <input
          type="password"
          class="form__input"
          autofocus
          placeholder="Password"
          name="password"
          required
        />
        <div class="form__input-error-message"></div>
      </div>
      
      <div class="form__input-group">
        <input
          type="checkbox"
          autofocus
          name="remember_me"
          value="true"
          id = "remember_me"
          checked
        />
        <label for="remember_me">Remember me</label>
        <div class="form__input-error-message"></div>
      </div>

      <input type="text" name="form-name" value="login-form" hidden />

      <button class="form__button" type="submit">Continue</button>
      <p class="form__text">
        <a href="#" class="form__link">Forgot your password?</a>
      </p>
      <p class="form__text">
        <a class="form__link" href="./" id="linkCreateAccount"
          >Don't have an account? Create account</a
        >
      </p>
    </form>

    <form class="form form--hidden" id="createAccount" method = "POST" action=<?php echo $controller_url ?>>
      <h1 class="form__title">Create Account</h1>
      <div class="form__message form__message--error"></div>
      <div class="form__input-group">
        <input
          type="text"
          id="name"
          class="form__input"
          autofocus
          placeholder="Name"
          name = "name"
        />
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <input
          type="text"
          id="signupUsername"
          class="form__input"
          autofocus
          placeholder="Username"
          name = "username"
        />
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <input
          type="text"
          class="form__input"
          autofocus
          placeholder="Email Address"
          name = "email"
        />
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <input
          type="password"
          class="form__input"
          autofocus
          placeholder="Password"
          name = "password"
        />
        <div class="form__input-message">
        </div>
      </div>
      <div class="form__input-group">
        <input
          type="password"
          class="form__input"
          autofocus
          placeholder="Confirm password"
          name = "confirm_password"
        />
        <div class="form__input-error-message"></div>
      </div>

      <input type="text" name="form-name" value="register-form" hidden />
      <button class="form__button" type="submit">Continue</button>
      <p class="form__text">
        <a class="form__link" href="./" id="linkLogin"
          >Already have an account? Sign in</a
        >
      </p>
    </form>
  </div>
  <script src="js/signIn.js"></script>
</body>
