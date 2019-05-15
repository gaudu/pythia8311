<html>
<head>
<title>The Settings Scheme</title>
</head>
<body>

<script language=javascript type=text/javascript>
function stopRKey(evt) {
var evt = (evt) ? evt : ((event) ? event : null);
var node = (evt.target) ? evt.target :((evt.srcElement) ? evt.srcElement : null);
if ((evt.keyCode == 13) && (node.type=="text"))
{return false;}
}

document.onkeypress = stopRKey;
</script>
<?php
if($_POST['saved'] == 1) {
if($_POST['filepath'] != "files/") {
echo "<font color='red'>SETTINGS SAVED TO FILE</font><br/><br/>"; }
else {
echo "<font color='red'>NO FILE SELECTED YET.. PLEASE DO SO </font><a href='SaveSettings.php'>HERE</a><br/><br/>"; }
}
?>

<form method='post' action='SettingsScheme.php'>

<h2>The Settings Scheme</h2>

The <code>Settings</code> class keeps track of all the flags, modes, 
parameters and words in the program. As such, it serves the other program 
elements 
from one central repository. It allows the user to modify the behaviour of 
the program. The <code>Settings</code> class is purely static, i.e. you can 
interact with it directly by <code>Settings::command(argument)</code>. 
However, a <code>settings</code> object of the <code>Settings</code> class 
is a public member of the <code>Pythia</code> class, so an alternative 
notation would be <code>pythia.settings.command(argument)</code>,
assuming that <code>pythia</code> is an instance of the <code>Pythia</code> 
class. Further, for the most frequent user tasks, <code>Pythia</code> 
methods have been defined, so that <code>pythia.command(argument)</code> 
would work, see further below.

<h3>Concepts</h3>

We distinguish four kinds of user-modifiable variables, by the way
they have to be stored:
<ol>
<li>Flags are on/off switches, and are stored as <code>bool</code>.</li>
<li>Modes corresponds to a finite enumeration of separate options,
   and are stored as <code>int</code>.</li>
<li>Parameters take a continuum of values, and are stored as 
<code>double</code>. The shorthand notation parm is used in the C++ 
code and XML tags, so that all four kinds are represented by
four-letter type names.</li>
<li>Words are simple character strings and are stored as 
<code>string</code>. No blanks or double quotation marks (") may 
appear inside a word, the former to simplify parsing of an input file
and the latter not to cause conflicts with XML attribute delimiters.
Currently the main application is to store file names.</li>
</ol>

<p/>
In general, each variable stored in <code>Settings</code> is associated 
with four kinds of information:
<ul>
<li>The variable name, of the form <code>class:name</code> 
(or <code>file:name</code>, usually these agree), e.g. 
<code>TimeShower:pTmin</code>. The class/file part usually identifies 
the <code>.xml</code> file where the variable is defined, and the part of 
the program where it is used, but such a connection cannot be strictly
upheld, since e.g. the same variable may be used in a few different
cases (even if most of them are not).</li> 
<li>The default value, set in the original declaration, and intended
to represent a reasonable choice.</li> 
<li>The current value, which differs from the default when the user so 
requests.</li>
<li>An allowed range of values, represented by meaningful
minimum and maximum values. This has no sense for a <code>flag</code> 
or a <code>word</code> (and is not used there), is usually rather 
well-defined for a <code>mode</code>, but less so for a <code>parm</code>. 
Often the allowed range exaggerates the degree of our current knowledge, 
so as not to restrict too much what the user can do. One may choose 
not to set the lower or upper limit, in which case the range is 
open-ended.</li>   
</ul>

<p/>
Technically, the <code>Settings</code> class is implemented with the 
help of four separate maps, one for each kind of variable, with the 
variable <code>name</code> used as key. 

<h3>Operation</h3>
    
The normal flow of setting values is:

<ol>

<p/> <li>
When a <code>Pythia</code> object <code>pythia </code>is created, 
the member <code>pythia.settings</code> is asked to scan the files
listed in the <code>Index.xml</code> file in the <code>xmldoc</code> 
subdirectory.

<p/>
In all of the files scanned, lines beginning with 
<code>&lt;flag</code>, <code>&lt;mode</code>, <code>&lt;parm</code> 
or <code>&lt;word</code> are identified, and the information on 
such a line is used to define a new flag, mode, parameter or word. 
To exemplify, consider a line
<pre>
&lt;parm name="TimeShower:pTmin" default="0.5" min="0.1" max="2.0">
</pre> 
which appears in the <code>TimeShower.xml</code> file, and there
defines a parameter <code>TimeShower:pTmin</code> with default value 
0.5 GeV and allowed variation in the range 0.1 - 2.0 GeV. The min 
and max values are optional.
<br/><b>Important:</b> the values in the <code>.xml</code> files should 
not be changed, except by the PYTHIA authors. Any changes should be 
done with the help of the methods described below.
</li>

<p/> <li>
Between the creation of the <code>Pythia</code> object and the 
<code>init</code> call for it, you may use several alternative 
methods to modify some of the default values. 

<p/> 
a) Inside your main program you can directly set values with
<pre>
    pythia.readString(string) 
</pre>
where both the variable name and the value are contained inside
the character string, separated by blanks and/or a =, e.g. 
<pre>
    pythia.readString("TimeShower:pTmin = 1.0"); 
</pre>
The match of the name to the database is case-insensitive. Names 
that do not match an existing variable are ignored. A warning is
printed, however, unless an optional second argument <code>false</code> 
is used. Strings beginning with a non-alphanumeric character, like 
# or !, are assumed to be comments and are not processed at all. 
Values below the minimum or above the maximum are set at 
the respective border. For <code>bool</code> values, the following 
notation may be used interchangeably: 
<code>true = on = yes = ok = 1</code>, while everything else gives 
<code>false</code> (including but not limited to 
<code>false</code>, <code>off</code>, <code>no</code> and 0).<br/> 

<p/> 
b) The <code>Pythia</code> <code>readString</code> method actually does
not do changes itself, but sends on the string either to the
<code>Settings</code> class or to <code>ParticleData</code>. 
If desired, it is possible to communicate
directly with the corresponding <code>Settings</code> method:
<pre>
    pythia.settings.readString("TimeShower:pTmin = 1.0"); 
</pre>
In this case, changes intended for <code>ParticleData</code> 
would not be understood.

<p/> 
c) Underlying the <code>settings.readString</code> method are the 
settings-type-sensitive commands in the <code>Settings</code>, that 
are split by names containing <code>flag</code>, <code>mode</code>, 
<code>parm</code> or <code>word</code>. Thus, the example now reads
<pre>
    pythia.settings.parm("TimeShower:pTmin", 1.0); 
</pre>
Boolean values should here be given as <code>true</code> or 
<code>false</code> i.e. there is less flexibility in the lower-level 
methods.

<p/> 
At the same level, there are several different methods available.
We here show the ones for <code>mode</code>, but corresponding methods 
exist for <code>flag</code>, <code>parm</code> and <code>word</code>,
with obvious restrictions where <code>min</code> and <code>max</code> 
are not defined. Again name comparisons are case-insensitive.
<p/><code>method&nbsp; </code><strong> mode( name) &nbsp;</strong> <br/>
gives the current value,
  
<p/><code>method&nbsp; </code><strong> mode( name, value) &nbsp;</strong> <br/>
sets the current value,
  
<p/><code>method&nbsp; </code><strong> isMode( name) &nbsp;</strong> <br/>
tells whether a mode has been defined or not,
  
<p/><code>method&nbsp; </code><strong> addMode( name, default, min, max) &nbsp;</strong> <br/>
defines a new mode,
  
<p/><code>method&nbsp; </code><strong> forceMode( name, value) &nbsp;</strong> <br/>
sets the value, also when outside the recommended bounds (and it is 
completely up to you to face the consequences),
  
<p/><code>method&nbsp; </code><strong> resetMode( name) &nbsp;</strong> <br/>
resets the current value to the default one.
  

<p/> 
Normally the user should have no need for these methods. The 
main exception is when some of the variables defined on the
<?php $filepath = $_GET["filepath"];
echo "<a href='MainProgramSettings.php?filepath=".$filepath."' target='page'>";?>Main-Program Settings</a> 
page are used to set run-specific information 
(like the CM energy or the number of events to generate) in an external 
file (see 2d below) and these variables are to be read into the main 
program. Then the <code>flag(name)</code>, <code>mode(name)</code>
<code>parm(name)</code> and <code>word(name)</code> methods are to 
be used, see e.g. the main programs in the <code>examples</code> 
subdirectory to find out how it works.

<p/>
d) A simpler and more useful way is to collect all your changes
in a separate file, with one line per change, e.g. 
<pre>
    TimeShower:pTmin = 1.0
</pre>
Each line is read in as a string and processes with the methods already
introduced.

The file can be read by the 
<pre>
    pythia.readFile("filename"); 
</pre>
method. The file can freely mix commands to the <code>Settings</code>
and <code>ParticleData</code> classes, and so is preferable. Lines with 
settings are handled by calls to the 
<code>pythia.settings.readString(...)</code> method. Again, an optional 
second argument <code>false</code> allows you to switch off warning 
messages for unknown variables.
</li>

<p/> <li>
In the <code>Pythia init</code> call, many of the various other program  
elements are initialized, making use of the current values in the database. 
Once initialized, the common <code>Settings</code> database is likely not 
consulted again by these routines. It is therefore not productive to do 
further changes in mid-run: at best nothing changes, at worst you may 
set up inconsistencies. 

<p/>
A routine <code>reInit("filename")</code> is provided, and can be used to 
zero all the maps and reinitialize  from scratch. Such a call might be 
required if several <code>Pythia</code> objects are created in the same run, 
and requested to have different values - by default the <code>init()</code> 
call is only made the first time. However, a more economical solution 
is then offered by <code>resetAll()</code>, which sets all variables to 
their default values.
</li> 

<p/> <li>
You may at any time obtain a listing of all variables in the 
database by calling  
<pre>
    pythia.settings.listAll();
</pre>
The listing is strictly alphabetical, which at least means that names
from the same file are kept together, but otherwise may not be so 
well-structured: important and unimportant ones will appear mixed.
A more relevant alternative is 
<pre>
    pythia.settings.listChanged();
</pre>
where you will only get those variables that differ from their 
defaults. Or you can use 
<pre>
    pythia.settings.list("string");
</pre>
where only those variables with names that contain the string 
(case-insensitive match) are listed. Thus, with a string 
<code>shower</code>, the shower-related variables would be shown.
</li>

<p/> <li>
The above listings are in a tabular form that cannot be read
back in. Assuming you want to save all changed settings (maybe because
you read in changes from several files), you can do that by calling
<pre>
    pythia.settings.writeFile("filename");
</pre>
This file could then directly be read in by 
<code>readFile("filename")</code> in a subsequent (identical) run.
A second argument <code>true</code> would print all settings, not
only the changed ones. Further, the first argument can be replaced by
(a reference to) an <code>ostream</code>, by default <code>cout</code>.   
</li>
</ol>

</body>
</html>

<!-- Copyright (C) 2007 Torbjorn Sjostrand -->