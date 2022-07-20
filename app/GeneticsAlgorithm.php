<?php

use App\Models\ClassTiming;
use App\Models\Course;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Schedule as ModelsSchedule;
use App\Models\Venue;

class GeneticAlgorithm {

    public $POPULATION_SIZE;
    public $NUMB_OF_ELITE_SCHEDULES;
    public $TOURNAMENT_SELECTION_SIZE;
    public $MUTATION_RATE;
    public $data;
    
    function __construct(){
        $this->POPULATION_SIZE = 3;
        $this->NUMB_OF_ELITE_SCHEDULES = 1;
        $this->TOURNAMENT_SELECTION_SIZE = 3;
        $this->MUTATION_RATE = 0.05;
        $this->data = new Data();
    }

    function evolve($population){
        return $this->_mutate_population($this->_crossover_population($population));
    }
    function _crossover_population($pop){
        $population = new Population(0);
        $crossover_pop = [];
        for ($i = 0;$i <= $this->NUMB_OF_ELITE_SCHEDULES; $i++){
            array_push($crossover_pop, $population->get_schedules(), $pop->get_schedules()[$i]);
            
            $i = $this->NUMB_OF_ELITE_SCHEDULES;
            while ($i < $this->POPULATION_SIZE){
                $schedule1 = $this->_select_tournament_population($pop)->get_schedules()[0];
                $schedule2 = $this->_select_tournament_population($pop)->get_schedules()[0];
                $population->set_schedule($this->_crossover_schedule($schedule1, $schedule2));
                $i += 1;
            }
        }
        return $crossover_pop;
    }
    function _mutate_population($population){
        for( $i = $this->NUMB_OF_ELITE_SCHEDULES; $i <= $this->POPULATION_SIZE; $i++){
            $this->_mutate_schedule($population->get_schedules()[$i]);
        }
        return $population;
    }

    function _crossover_schedule($schedule1, $schedule2){
        $schedule = new Schedule();
        $schedule->initialize();
        for ($i = 0; $i <= $schedule->get_classes(); $i++){
            if (rand() > 0.5)
                $schedule->get_classes()[$i] = $schedule1->get_classes()[$i];
            else
                $schedule->get_classes()[$i] = $schedule2->get_classes()[$i];
        }
        return $schedule;
    }

    function _mutate_schedule($mutateSchedule){
        $schedule = new Schedule();
        $schedule->initialize();
        for($i = 0;  $i <= $mutateSchedule->get_classes() ; $i++){
            if ($this->MUTATION_RATE > rand()){
                $mutateSchedule->get_classes()[$i] = $schedule->get_classes()[$i];
            }
        }
        return $mutateSchedule;
    }

    function _select_tournament_population($pop){
        $tournament_pop = new Population(0);
        $i = 0;
        while( $i < $this->TOURNAMENT_SELECTION_SIZE){
            $tournament_pop->set_schedule($pop->get_schedules()[rand(0, $this->POPULATION_SIZE)]);
            $i += 1;
        }
        // $tournament_pop.get_schedules().sort(key=lambda x: x.get_fitness(), reverse=true);
        return $tournament_pop;
    }

    function context_manager($schedule){
        $classes = $schedule->get_classes();
        $context = [];
        $cls = [];
        for ($i=0;$i <= sizeof($$classes); $i++){
            $cls["section"] = $classes[$i]->section_id;
            $cls['dept'] = $classes[$i]['department']->dept_name;
            // $cls['course'] = f'{$classes[$i]->course.course_name} ({$classes[$i].course.course_number}, ' \
            //                 f'{$classes[$i].course.max_numb_students}';
            // $cls['venue'] = f'{$classes[$i].venue.r_number} ({$classes[$i].venue.seating_capacity})';
            // $cls['lecturer'] = f'{$classes[$i].lecturer.name} ({$classes[$i].lecturer.uid})';
            $cls['meeting_time'] = [
                $classes[$i]['meeting_time']->id,
                $classes[$i]['meeting_time']->day,
                $classes[$i]['meeting_time']->time
            ];
            array_push($context, $cls);
            
        }
        return $context;
    }

    function timetable() {
        $schedule = [];
        $population = new Population($this->POPULATION_SIZE);
        $generation_num = 0;
        // $population.get_schedules().sort(key=lambda x: x.get_fitness(), reverse=true);
        $geneticAlgorithm = new GeneticAlgorithm();

        while ($population->get_schedules()[0]->get_fitness() != 1.0){
            $generation_num += 1;
            // print('\n> Generation #' + str(generation_num));
            $population = $geneticAlgorithm->evolve($population);
            // $population->get_schedules().sort(key=lambda x: x.get_fitness(), reverse=true);
            $schedule = $population->get_schedules()[0]->get_classes();

            if($generation_num >= 15000) break;
        }
        return [
            'schedule'=> $schedule,
            'sections' =>  ModelsSchedule::all(),
            'times' => ClassTiming::all(),
        ];

    }
}

class Data{
    public $_venues;
    public $_meetingTimes;
    public $_lecturers;
    public $_courses;
    public $_depts;

    function Data(){
        $this->_venues = Venue::all();
        $this->_meetingTimes = ClassTiming::all();
        $this->_lecturers = Lecturer::all();
        $this->_courses = Course::all();
        $this->_depts = Department::all();
    }
    function get_venues(){ return $this->_venues;}

    function get_lecturers(){return $this->_lecturers;}

    function get_courses(){ return $this->_courses;}

    function get_depts(){ return $this->_depts;}

    function get_meetingTimes(){ return $this->_meetingTimes;}
}

class ClassSession{
    public $section_id;
    public $department;
    public $course;
    public $lecturer;
    public $meeting_time;
    public $venue;
    public $section;

    function ClassSession( $id, $dept, $section, $course){
        $this->section_id = $id;
        $this->department = $dept;
        $this->course = $course;
        $this->lecturer = null;
        $this->meeting_time = null;
        $this->venue = null;
        $this->section = $section;
    }

    function get_id(){ return $this->section_id;}

    function get_dept(){ return $this->department;}

    function get_course(){ return $this->course;}

    function get_lecturer(){ return $this->lecturer;}

    function get_meetingTime(){ return $this->meeting_time;}

    function get_venue(){ return $this->venue;}

    function set_lecturer( $lecturer){ $this->lecturer = $lecturer;}

    function set_meetingTime( $meetingTime){ $this->meeting_time = $meetingTime;}

    function set_venue( $venue){ $this->venue = $venue;}
}

class Schedule {

    public $_data;
    public $_classes;
    public $_numberOfConflicts;
    public $_fitness;
    public $_classNumb;
    public $_isFitnessChanged;

    function Schedule(){
        $this->_data = new Data();
        $this->_classes = [];
        $this->_numberOfConflicts = 0;
        $this->_fitness = -1;
        $this->_classNumb = 0;
        $this->_isFitnessChanged = true;
    }
    function get_classes(){
        $this->_isFitnessChanged = true;
        return $this->_classes;
    }
    function get_numbOfConflicts(){
        return $this->_numberOfConflicts;
    }

    function get_fitness(){
        if ($this->_isFitnessChanged){
            $this->_fitness = $this->calculate_fitness();
            $this->_isFitnessChanged = False;
        }
        return $this->_fitness;
    }
    function initialize(){
        $sections = ModelsSchedule::all();
        foreach ($sections as $section){
            $dept = $section->department;
            $n = $section->num_class_in_week;
            if( $n <= sizeof(ClassTiming::all()) ){
                $courses = $dept->get_courses();
                foreach( $courses as $course){
                    for( $i = 0; $i <= ($n / sizeof($courses)) ; $i++){
                        $crs_inst = $course->lecturers;
                        $newClass = new ClassSession($this->_classNumb, $dept, $section->section_id, $course);
                        $this->_classNumb += 1;
                        $newClass->set_meetingTime($this->_data->get_meetingTimes()[rand(0, sizeof(ClassTiming::all()))]);
                        $newClass->set_venue($this->_data->get_venues()[rand(0, sizeof($this->_data->get_venues()))]);
                        $newClass->set_lecturer($crs_inst[rand(0, sizeof($crs_inst))]);
                        array_push($this->_classes, $newClass);
                    }
                }
            }
            else{
                $n = sizeof(ClassTiming::all());
                $courses = $dept->get_courses();
                foreach( $courses as $course){
                    for ($i=0 ;$i <= ($n / sizeof($courses)); $i++) {
                        $crs_inst = $course->lecturers();
                        $newClass = new ClassSession($this->_classNumb, $dept, $section->section_id, $course);
                        $this->_classNumb += 1;
                        $newClass->set_meetingTime($this->_data->get_meetingTimes()[rand(0, sizeof(ClassTiming::all()))]);
                        $newClass->set_venue($this->_data->get_venues()[rand(0, sizeof($this->_data->get_venues()))]);
                        $newClass->set_lecturer($crs_inst[rand(0, sizeof($crs_inst), 1)]);
                        array_push($this->_classes, $newClass);
                    }
                }
            }
        }
    }

    function calculate_fitness(){
        $this->_numberOfConflicts = 0;
        $classes = $this->get_classes();
        for ($i = 0; $i <= sizeof($classes); $i++ ){
            if ($classes[$i]['venue']->seating_capacity < $classes[$i]['course']->max_numb_students ){
                $this->_numberOfConflicts += 1;
            }
            for ($j = 0; $j <= sizeof($classes); $j++){
                if ($j >= $i){
                    if (
                        ($classes[$i]->meeting_time == $classes[$j]->meeting_time) &&
                        ($classes[$i]->section_id != $classes[$j]->section_id) &&
                        ($classes[$i]->section == $classes[$j]->section)
                      ){
                        if ($classes[$i]->venue == $classes[$j]->venue){
                            $this->_numberOfConflicts += 1;
                        }
                        if( $classes[$i]->lecturer == $classes[$j]->lecturer){
                            $this->_numberOfConflicts += 1;
                        }
                        return 1 / (1.0 * $this->_numberOfConflicts + 1);
                    }
                }
            }
        }
    }
}
class Population{
    public $_data;
    public $schedule;
    public $_size;
    public $_schedules;

    function Population( $size ){
        $this->_data = new Data();
        $this->schedule = new Schedule();
        $this->_size = $size;
        $this->_schedules = $this->schedule->classes;
    }

    function get_schedules(){
        return $this->_schedules;
    }

    function set_schedule($schedule){
        array_push($this->_schedules, $schedule);
    }

}