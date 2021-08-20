# basic
The basic repository of the sunhill frameworks provides some fundamental classes for the other classes of the sunhill framework. These are mainly four parts:
- fundamental classes (base and loggable, SunhillException
- utility class (descriptor)
- checker subsystem
- test subsystem

## Fundamental classes

The basic repository provides two basic classes and one basic exception. These are:

### base (defined in src/base.php) 
The base class is the basic class for other classes of the framework. It defines the possibility to read unkown properties via get_ and write via set_ methods. 

### loggable (defined in src/loggable.php)
This class was planned to abstract the possibility of logging. Perhaps this class is obsolete.

### SunhillException (defined in src/SunhillException.php)
A basic exception

## Utility class

### descriptor
A descriptor is a simple class, that is able to store data just like StdClass could but with some additional gadgets. 

## Checker subsystem
The checker subsystem provides a system for checking the integrety of the sunhill system. Therfore the command line command "check" is defined and a checker facade is provided. The subsystems of the sunhill framework can add their own checks to the checker system. A call of ./artisan sunhill:check will call all checks in order. 

## Test subsystem
In opposite to laravel the sunhill framework uses so called scenarios to set up tests. Two helper traits are defined: scenariowithfiles and scenariowithdatabase. 
 