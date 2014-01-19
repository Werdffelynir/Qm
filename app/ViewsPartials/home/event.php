<div class="page-header">
    <h3><?=$this->data['title'];?></h3>
</div>
<br/>
<hr/>
<h2>Использовагте статического метода евента App::eventRegister</h2>
<?php
/* Использовагте статического метода евента App::eventRegister */

App::eventTrigger('e-01');
App::eventTrigger('e-02');
App::eventTrigger('e-03', array('Petro'));
App::eventTrigger('e-04', array('Имя Первое','Имя второе'));
?>

<br/>
<hr/>
<h2>Использовагте метода евента $this->hookRegister</h2>
<?php
/* Использовагте метода евента $this->hookRegister */
$this->hookTrigger('h-00');
$this->hookTrigger('h-01');
$this->hookTrigger('h-02', array('Миша'));
$this->hookTrigger('h-03', array('Вася','Даша'));


//echo App::eventTrigger('te');
//$this->hookTrigger('ane');
//$this->hookTrigger('nameTwo'/*, 'Petia+'*/);

//echo App::eventTrigger('eve');
//echo App::eventTrigger('eveName', array('eveName Petia'));
//var_dump(App::eventRegister());

//$this->hook('ane', array('three', 'fore'));
//echo $this->hookTrigger('testE');
?>

<p>++++++</p>

<?php
App::filterTrigger('f_01',  "test string");
?>

<p>++++++</p>