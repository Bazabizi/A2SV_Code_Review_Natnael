<?php
require_once "pdo.php";
session_start();

require_once "pdo.php";
$sql = "select user.fullname , questions.question, answers.answer from answers Join 
user on answers.user_id = user.id
join questions on questions.question_id = answers.ques_id";


$stmt = $pdo->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION['token'])) {
    // Redirect to login page if token is not set
    header('Location: login.php');
    exit;
}

$token = $_SESSION['token'];

$stmt = $pdo->prepare('SELECT * FROM User WHERE token = ?');
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    // Redirect to login page if token is invalid
    header('Location: login.php');
    exit;
}

if(isset($_POST['search'])){

    $sentences = $_POST['search'];
    $words = explode(" " , $sentences);
    
    $sql = "SELECT user.id , user.fullname , questions.question_id, questions.question , answers.answer FROM answers JOIN questions ON questions.question_id = answers.ques_id 
    join user on user.id = answers.user_id WHERE ";
    
    foreach ($words as $word) {
        $sql .= "questions.question LIKE '%" . $word . "%' AND ";
    }
    
    $sql = substr($sql, 0, -5); // remove the last ' AND '
    $sql .= " GROUP BY questions.question_id HAVING COUNT(*) >= 2";

    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <link href="main.css" rel="stylesheet" />
    <script defer src="/register.js"></script>

    <title>Hello, world!</title>
  </head>
  <body>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->

    <nav class="navbar navbar-expand-md navbar-light border-bottom p-0 ps-5">
      <div class="container">
        <a class="navbar-brand" href="#">
          <span class="text-success fw-bold fs-3">Afri-Ask</span>
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <div class="container justify-content-end">
            <ul class="navbar-nav container justify-content-end">
              <li class="nav-item nav-dark">
                <a class="nav-link" href="index.html">
                  <svg
                    class="text-success"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 96 960 960"
                    fill="currentColor"
                  >
                    <path
                      d="M220 876h150V626h220v250h150V486L480 291 220 486v390Zm-60 60V456l320-240 320 240v480H530V686H430v250H160Zm320-353Z"
                    />
                  </svg>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="questions.html">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    height="20"
                    viewBox="0 96 960 960"
                    width="20"
                    fill="currentColor"
                  >
                    <path
                      d="M180 1044q-24 0-42-18t-18-42V384q0-24 18-42t42-18h405l-60 60H180v600h600V636l60-60v408q0 24-18 42t-42 18H180Zm300-360Zm182-352 43 42-285 284v86h85l286-286 42 42-303 304H360V634l302-302Zm171 168L662 332l100-100q17-17 42.311-17T847 233l84 85q17 18 17 42.472T930 402l-97 98Z"
                    />
                  </svg>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="">
                  <div class="popup-container">
                    <img
                      src="src/profile2.png"
                      alt="Profile picture"
                      id="profile-picture"
                      class="profile rounded-circle"
                    />
                    <div class="popup" id="popup">
                      <p class="fw-bold">Name Username</p>
                    </div>
                  </div>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="Homepage.html">
                  <svg xmlns="http://www.w3.org/2000/svg" height="48" 
                  viewBox="0 96 960 960" 
                  width="48"
                  fill="currentColor">
                    <path d="M180 936q-24 0-42-18t-18-42V276q0-24 18-42t42-18h291v60H180v600h291v60H180Zm486-185-43-43 102-102H375v-60h348L621 444l43-43 176 176-174 174Z"/></svg>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <div class="bg-light pt-4">
      <div class="container mb-5">
        <div class="row">
          <div class="col-8 container justify-content-center">
            <div class="bg-white border-grey">
              <div class="row">
                <div class="col"></div>
                <div class="row text-gray-darker pb-2 ps-4 pe-4">
                  <div class="py-3">
                    <form method="POST" class="d-flex">
                      <input
                        class="form-control me-2 search-icon ms-4"
                        name="search"
                        type="search"
                        placeholder="Search Afri-Ask"
                        width="90"
                        height="100"
                      />
                    </form>
                  </div>
                  <div class="col text-center border-end hover-dark">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      height="20px"
                      viewBox="0 96 960 960"
                      width="20px"
                      fill="currentColor"
                    >
                      <path
                        d="M480 1016 360 896H180q-24 0-42-18.5T120 836V236q0-24 18-42t42-18h600q23 0 41.5 18t18.5 42v600q0 23-18.5 41.5T780 896H600l-120 120ZM180 836h204l96 96 96-96h204V236H180v600Zm0-600v600-600Zm297.028 522Q493 758 504 746.972q11-11.028 11-27T503.972 693q-11.028-11-27-11T450 693.028q-11 11.028-11 27T450.028 747q11.028 11 27 11ZM501 610q0-31 10-50.5t34.721-44.221Q579 482 592 456.5t13-53.5q0-53-34-84t-91.523-31q-51.866 0-88.171 24.5Q355 337 338 380l53 22q14-28 36.2-42.5Q449.4 345 479 345q32 0 50.5 16t18.5 44.098Q548 425 537 443.5T500 487q-37 35-46.5 60t-9.5 63h57Z"
                      />
                    </svg>
                    <a href="ask.php" class="button-link">Ask</a>
                  </div>

                  <div class="col text-center border-end hover-dark">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      height="20"
                      viewBox="0 96 960 960"
                      width="20"
                      fill="currentColor"
                    >
                      <path
                        d="M180 1044q-24 0-42-18t-18-42V384q0-24 18-42t42-18h405l-60 60H180v600h600V636l60-60v408q0 24-18 42t-42 18H180Zm300-360Zm182-352 43 42-285 284v86h85l286-286 42 42-303 304H360V634l302-302Zm171 168L662 332l100-100q17-17 42.311-17T847 233l84 85q17 18 17 42.472T930 402l-97 98Z"
                      />
                    </svg>
                    <span>
                      <a href="questions.php" class="button-link">Answer</a>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="post bg-white border=gray mt-4">
              <div class="pt-2 d-flex justify-content-between">
                <ul>
                <?php

                if(!$results){
                  echo "NO DATA IS found";
                  }
                 foreach($results as $row) { ?>
                   <li>
                    <!-- <?php
                    echo $row['name']."\n";
                    echo $row['question']."\n";
                    echo $row['answer']."\n";
                    ?> -->
                <div class="d-flex flex-column">
                  <span class="fw-bold fs-6">
                    <?php
                    echo $row['question']."\n";
                  ?></span>
                  <span class="text-grey-darker fs-6">
                    <?php  echo $row['fullname']."\n";
                  ?></span>
                  <br />
                  <span>
                    <?php
                    echo $row['answer']."\n";
                    ?>
                  </span>
                  <div class="post-footer pt-2 pb-1 ps-2">
                    <div class="btn-group" role="group">
                      <div class="pe-2">
                        <a href="answer.html" class="button-link"
                          ><svg
                            xmlns="http://www.w3.org/2000/svg"
                            height="20"
                            viewBox="0 96 960 960"
                            width="20"
                            fill="currentColor"
                          >
                            <path
                              d="M180 1044q-24 0-42-18t-18-42V384q0-24 18-42t42-18h405l-60 60H180v600h600V636l60-60v408q0 24-18 42t-42 18H180Zm300-360Zm182-352 43 42-285 284v86h85l286-286 42 42-303 304H360V634l302-302Zm171 168L662 332l100-100q17-17 42.311-17T847 233l84 85q17 18 17 42.472T930 402l-97 98Z"
                            />
                          </svg>
                          <span> Answer </span></a
                        >
                      </div>

                      <button
                        type="button"
                        class="left-button post-button bg-second-color border-0 text-black p-1"
                      >
                        <img src="src/up.png" width="20" alt="" class="me-2" />
                        15
                      </button>
                      <button
                        type="button"
                        class="right-button post-button bg-second-color border-0 text-black p-1"
                      >
                        <img src="src/down.png" width="20" alt="" />
                      </button>

                      <button
                        type="button"
                        class="post-button bg-second-color rounded-circle border-0 text-black p-1"
                      >
                        <img src="src/share.png" width="20" alt="" />
                        1
                      </button>
                      <button
                        type="button"
                        class="post-button bg-second-color rounded-circle border-0 text-black p-1"
                      >
                        <img src="src/comment.png" width="20" alt="" />
                        1
                      </button>
                    </div>
                  </div>
                </div>

                    </li>
                  <?php
                  }
                  ?>
                  
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
