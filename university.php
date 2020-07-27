<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title></title>
</head>

<body>

  <?php include "header.html" ?>
  <!--Html form for user input -->
  <form action="university.php" method="post">
    Student ID: <input type="text" name="studid"><br>
    Full name: <input type="text" name="fullname"><br>
    Language: <input type="radio" id="english" name="language" value="en">
    <label for="english">English</label>
    <input type="radio" id="german" name="language" value="de">
    <label for="german">German</label>
    <input type="radio" id="romanian" name="language" value="ro">
    <label for="romanian">Romanian</label><br>
    Exam Fee Paid: <input type="checkbox" name="examfee"><br>
    <input type="submit" value="Submit">



  </form>


  <?php

  // abstract class for Student 
  abstract class Student
  {
    use ArrayOrJson; 

    public $studId;
    protected $fullName;
    protected $languageDiff;
    protected $paystat;

    public function __construct($studId)
    {
      $this->studId = $studId;
    }

    public function setFullName($name)
    {
      $this->fullName = $name;
    }
    public function getFullName()
    {
      return $this->fullName;
    }

    public function setLanguageDiff($diff = null)
    {
      $this->languageDiff = $diff;
    }
    public function getLanguageDiff()
    {
      return $this->languageDiff;
    }
    public function getPayStatus()
    {
      return $this->paystat;
    }

    abstract public function examFeePaid($payed);
    abstract public function discountGiven();
  }

// trait for generating a json or array from the input data
  trait ArrayOrJson
  {
    public function asArray(): array
    {
      return get_object_vars($this);
    }

    public function asJson(): string
    {
      return json_encode($this->asArray());
    }
  }



  // three type of students 
  class English extends Student
  {
    use ArrayOrJson; 

    public function setLanguageDiff($diff = 2)
    {
      $this->languageDiff = $diff;
    }

    public function examFeePaid($payed)
    {
      if ($payed) {
        $this->paystat = "Exam fee is payed!";
      } else {
        $this->paystat = "Exam fee is not payed!";
      }
    }

    public function discountGiven()
    {
      return $discount = $this->languageDiff * 0.10;
    }
  }

  class German extends Student
  {
    use ArrayOrJson; 

    public function setLanguageDiff($diff = 3)
    {
      $this->languageDiff = $diff;
    }

    public function examFeePaid($payed)
    {
      if ($payed) {
        $this->paystat = "Exam fee is payed!";
      } else {
        $this->paystat = "Exam fee is not payed!";
      }
    }
    public function discountGiven()
    {
      return $discount = $this->languageDiff * 0.10;
    }
  }

  class Romanian extends Student
  {
    use ArrayOrJson; 

    public function setLanguageDiff($diff = 1)
    {
      $this->languageDiff = $diff;
    }

    public function examFeePaid($payed)
    {
      if ($payed) {
        $this->paystat = "Exam fee is payed!";
      } else {
        $this->paystat = "Exam fee is not payed!";
      }
    }
    public function discountGiven()
    {
      return $discount = $this->languageDiff * 0.10;
    }
  }


  //making part of factory pattern - generating exam
  class ExamFactory
  {

    protected $exam;

    public function take($lang = null)
    {
      switch (strtolower($lang)) {
        case 'en':
          return $this->exam = new ExamTypeEN();
          break;
        case 'de':
          return $this->exam = new ExamTypeDE();
          break;
        case 'ro':
          return $this->exam = new ExamTypeRO();
          break;
        default:
          echo "The choosen language is invalid!";
          break;
      }
    }

    public function getFExam()
    {
      return $this->exam;
    }
  }

  // managing part of factory pattern - picking exam
  class ExamPick
  {
    protected $examPicks = array();
    protected $exam;

    public function __construct()
    {
      $this->exam = new ExamFactory();
      //echo "exam created";
    }
    // picks an interface based on choosen language
    public function pick($lang = null)
    {
      //echo "exam picked";
      //debug($this->exam);
      $exam = $this->exam->take($lang);
      // debug($this->exam);
      //echo "exam taken";
      $this->examPicks[] = $exam->getLanguage();
    }
    public function showDetails()
    {

      return $this->examPicks;
    }

    public function getExamPicks()
    {
      return $this->examPicks;
    }

    public function getExamLang()
    {
      return $this->exam->getFExam()->getLanguage();
    }
    public function getExamDiff()
    {
      return $this->exam->getFExam()->getDifficulty();
    }
    public function getExamExaminer()
    {
      return $this->exam->getFExam()->getExaminer();
    }
  }

  //interface for exam types

  interface Exam
  {
    function getLanguage();

    function getDifficulty();

    function getExaminer();
  }

  class ExamTypeEN implements Exam
  {
    protected $lang = 'en';
    protected $difficulty = 'medium';
    protected $examiner = 'Dr.Stephen Strange';

    public function getLanguage()
    {
      return $this->lang;
    }

    public function getDifficulty()
    {
      return $this->difficulty;
    }

    public function getExaminer()
    {
      return $this->examiner;
    }
  }
  class ExamTypeDE implements Exam
  {
    protected $lang = 'de';
    protected $difficulty = 'hard';
    protected $examiner = 'Dr.Adolf Beckenbauer';

    public function getLanguage()
    {
      return $this->lang;
    }

    public function getDifficulty()
    {
      return $this->difficulty;
    }

    public function getExaminer()
    {
      return $this->examiner;
    }
  }
  class ExamTypeRO implements Exam
  {
    protected $lang = 'ro';
    protected $difficulty = 'easy';
    protected $examiner = 'Dr.Nelu Dancila';

    public function getLanguage()
    {
      return $this->lang;
    }

    public function getDifficulty()
    {
      return $this->difficulty;
    }

    public function getExaminer()
    {
      return $this->examiner;
    }
  }

  // testing
  $examPick = new ExamPick;
  switch ($_POST["language"]) {
    case 'en':
      $student1 = new English($_POST["studid"]);
      $student1->setFullName($_POST["fullname"]);
      $student1->setLanguageDiff();
      $student1->examFeePaid($_POST["examfee"]);
      $examPick->pick('en');
      break;

    case 'de':
      $student1 = new German($_POST["studid"]);
      $student1->setFullName($_POST["fullname"]);
      $student1->setLanguageDiff();
      $student1->examFeePaid($_POST["examfee"]);
      $examPick->pick('de');
      break;
    case 'ro':
      $student1 = new Romanian($_POST["studid"]);
      $student1->setFullName($_POST["fullname"]);
      $student1->setLanguageDiff();
      $student1->examFeePaid($_POST["examfee"]);
      $examPick->pick('ro');
      break;
    default:
      echo "Choose language!";
      break;
  }

  //debug($student1);
  //debug($examPick);


  function debug($variable)
  {
    echo "<pre> ";
    var_dump($variable);
    echo "</pre>";
  }
  ?>

  <!-- Display data -->
  <hr>
  <h3>Personal data:</h3>
  Your student id: <?php echo $student1->studId; ?><br>
  Your full name is : <?php echo $student1->getFullName(); ?><br>
  Discount given: <?php echo ($student1->discountGiven() * 100); ?>%<br>
  Payment status: <?php echo $student1->getPayStatus(); ?>

  <h3>Exam details:</h3><br>
  Exam language code: <?php echo $examPick->getExamLang(); ?><br>
  Choosen language difficulty: <?php echo $student1->getLanguageDiff(); ?> - <?php echo $examPick->getExamDiff(); ?><br>
  Examiner proffessor: <?php echo $examPick->getExamExaminer(); ?><br>

  <h5>JSON</h5><br>
  <?php 
  var_dump($student1->asJson());
  ?>


</body>

</html>