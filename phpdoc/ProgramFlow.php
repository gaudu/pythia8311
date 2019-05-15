<html>
<head>
<title>Program Flow</title>
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

<form method='post' action='ProgramFlow.php'>

<h2>Program Flow</h2>

Recall that, to first order, the event generation process can be 
subdivided into three stages:
<ol>
<li>Initialization.</li>
<li>The event loop.</li>
<li>Finishing.</li>
</ol>
This is reflected in how the top-level <code>Pythia</code> class should 
be used in the user-supplied main program, further outlined in the 
following. Since the nature of the run is defined at the initialization
stage, this is where most of the PYTHIA user code has to be written. 
So as not to confuse the reader unduly, the description of initialization 
options has been subdivided into what would normally be used and what is 
intended for more special applications.

<h3>Initialization - normal usage</h3>

<ol>

<li>
Already at the top of the main program file, you need to include the proper 
header file
<pre>
    #include "Pythia.h"
</pre>
To simplify typing, it also makes sense to declare
<pre>
    using namespace Pythia8; 
</pre>
</li>

<p/>
<li>
The first step is to create a generator object, 
e.g. with
<pre>
     Pythia pythia;
</pre>
It is this object that we will use from now on. Normally a run
will only contain one <code>Pythia</code> object. (Hypothetically 
you  could use several <code>Pythia</code> objects sequentially, 
but if done in parallel the <code>static</code> character of some
program elements is likely not to give the desired behaviour.)<br/>
By default all output from <code>Pythia</code> will be on the 
<code>cout</code> stream, but the <code>list</code> methods below do
allow output to alternative streams or files (by an optional
last argument, a reference to an <code>ostream</code>, usually not
explicitly written out here).
</li>  

<p/>
<li> 
You next want to set up the character of the run. 
The pages under the "Setup Run Tasks" heading in the index
describe all the options available (with some very few exceptions,
found on the other pages).  
The default values and your modifications are stored in two databases, 
one for <?php $filepath = $_GET["filepath"];
echo "<a href='SettingsScheme.php?filepath=".$filepath."' target='page'>";?>generic settings</a>
and one for <?php $filepath = $_GET["filepath"];
echo "<a href='ParticleDataScheme.php?filepath=".$filepath."' target='page'>";?>particle data</a>. 
Both of these are static classes, and are 
initialized with their default values by the <code>Pythia</code> 
constructor. The default values can then be changed, primarily
by one of the two ways below, or by a combination of them.<br/>  

<p/>
a) You can use the dedicated methods of each class to change the 
database default values to fit the needs of your current run. However, 
the
<pre>
    pythia.readString(string);
</pre>
method provides a covenient uniform interface to all of them.
The information in the string is case-insensitive, but upper- and
lowercase can be combined for clarity. The rules are that<br/>
(i) if the first nonblank character of the string is not a 
letter or a digit nothing will be done;<br/>
(ii) if the string begins with a digit it is assumed to contain 
particle data updates, and so sent on to 
<code>pythia.particleData.readString(string)</code>;<br/>
(iii) if none of the above, the string is assumed to contain a  
setting, and is sent on to 
<code>pythia.settings.readString(string)</code>.<br/> 
In the latter two cases, a warning is issued whenever a string
cannot be recognized (maybe because of a spelling mistake),
unless an optional second argument <code>false</code> is used to 
switch off warnings.<br/>
Some examples would be
<pre>
    pythia.readString("111:mayDecay = false");
    pythia.readString("TimeShower:pTmin = 1.0");
</pre>
The <code>readString(...)</code> method is intended primarily for 
a few changes. It can also be useful if you want to construct a
parser of input files that contain commands to several different 
libraries.<br/>

<p/>
b) You can read in a file containing a list of those variables 
you want to see changed, with a 
<pre>
    pythia.readFile(fileName);
</pre>
Each line in this file with be processes by the 
<code>readString(...)</code> method introduced above. You can thus 
freely mix comment lines and lines handed on to <code>Settings</code> 
or to <code>ParticleDataTable</code>.   
Again, an optional second argument <code>false</code> allows 
you to switch off warning messages for unknown variables.<br/>
This approach is better suited for more extensive changes than a direct
usage of <code>readString(...)</code>, and can also avoid having to
recompile and relink your main program between runs.
</li>

<p/>
<li>
Next comes the initialization stage, where all 
remaining details of the generation are to be specified. The 
<code>init(...)</code> method allows a few different input formats, 
so you can pick the one convenient for you:<br/>

<p/>
a) <code>pythia.init( idA, idB, eA, eB);</code><br/>
lets you specify the identities and energies of the two incoming
beam particles, with A (B) assumed moving in the <i>+z (-z)</i> 
direction.<br/>

<p/>
c) <code>pythia.init( idA, idB, eCM);</code><br/>
is similar, but you specify the CM energy, and you are assumed 
in the rest frame.<br/>

<p/>
d) <code>pythia.init();</code><br/>
with no arguments will read the beam parameters from the 
<?php $filepath = $_GET["filepath"];
echo "<a href='MainProgramSettings.php?filepath=".$filepath."' target='page'>";?><code>Main</code></a> 
group of variables. If you don't change any of those you will default 
to proton-proton collisions at 14 TeV, i.e. the nominal LHC values.
<br/>

<p/>
e) <code>pythia.init( LHAinit*, LHAevnt*);</code> <br/>
assumes Les Houches Accord [<a href="Bibliography.php" target="page">Boo01</a>] initialization information 
is available in an <code>LHAinit</code> class object, and that LHA event 
information will be provided by the <code>LHAevnt</code> class object,
and that pointers to these objects are handed in
(<?php $filepath = $_GET["filepath"];
echo "<a href='LesHouchesAccord.php?filepath=".$filepath."' target='page'>";?>further instructions</a>).<br/>

<p/>
d) <code>pythia.init( "filename");</code> <br/> 
assumes a file in the Les Houches Event File format [<a href="Bibliography.php" target="page">Alw06</a>] is 
provided (<?php $filepath = $_GET["filepath"];
echo "<a href='LesHouchesAccord.php?filepath=".$filepath."' target='page'>";?>further instructions</a>).
</li>

<p/>
<li>
If you want to have a list of the generator and particle data used, 
either only what has been changed or everything, you can use 
<pre>
    pythia.settings.listChanged();
    pythia.settings.listAll();
    pythia.particleData.listChanged(); 
    pythia.particleData.listAll(); 
</pre>
</li>

</ol>

<h3>Initialization - advanced usage</h3>

A) Necessary data are automatically loaded when you use the 
default PYTHIA installation directory structure and run the main 
programs in the <code>examples</code> subdirectory. However, in the 
general case, you must provide the path to the <code>.xml</code> files, 
originally stored in the <code>xmldoc</code> directory, where default 
settings and particle data are found. This can be done in two ways.

<ol>

<li>
You can set the environment variable <code>PYTHIA8DATA</code> to
contain the location of the <code>xmldoc</code> directory. In the
<code>csh</code> and <code>tcsh</code> shells this could e.g. be 
<pre>
     setenv PYTHIA8DATA /home/myname/pythia8090/xmldoc
</pre>
while in other shells it could be
<pre>
     export PYTHIA8DATA=/home/myname/pythia8090/xmldoc
</pre>
Recall that environment variables set locally are only defined in the 
current instance of the shell. The above lines should go into your 
<code>.cshrc</code> and <code>.bashrc</code> files, respectively, 
if you want a more permanant assignment.
</li>

<p/>
<li>
You can provide the path as argument to the <code>Pythia</code>
constructor, e.g.
<pre>
     Pythia pythia("/home/myname/pythia8090/xmldoc");
</pre>
</li>
</ol>
When <code>PYTHIA8DATA</code> is set it takes precedence, else 
the path in the constructor is used, else one defaults to the 
<code>../xmldoc</code> directory.

<p/>
B) You can override the default behaviour of PYTHIA not only by the 
settings and particle data, but also by replacing some of the 
PYTHIA standard routines by ones of your own. Of course, this is only
possible if your routines fit into the general PYTHIA framework.
Therefore they must be coded according to the the rules relevant
in each case, as a derived class of a PYTHIA base class, and a pointer 
to such an object must be handed in by one of the methods below.
These calls must be made before the <code>pythia.init(...)</code> call.

<ol>

<li>
If you are not satisfied with the list of parton density functions that 
are implemented internally or available via the LHAPDF interface
(see <?php $filepath = $_GET["filepath"];
echo "<a href='PDFSelection.php?filepath=".$filepath."' target='page'>";?>PDF Selection</a> page), you 
can suppy your own by a call to the <code>setPDFPtr(...)</code> method
<pre>
      pythia.setPDFptr( pdfAPtr, pdfBPtr); 
</pre>
where <code>pdfAPtr</code> and <code>pdfBPtr</code> are pointers to 
two <code>Pythia</code> PDF objects 
(<?php $filepath = $_GET["filepath"];
echo "<a href='PartonDistributions.php?filepath=".$filepath."' target='page'>";?>further instructions</a>). 
Note that <code>pdfAPtr</code> and <code>pdfBPtr</code> cannot point 
to the same object; even if the PDF set is the same, two copies are 
needed to keep track of two separate sets of <i>x </i>and density 
values.<br/>
If you further wish to use separate PDF's for the hard process of an
event than the ones being used for everything else, the extended form
<pre>
      pythia.setPDFptr( pdfAPtr, pdfBPtr, pdfHardAPtr, pdfHardBPtr); 
</pre>
allows you to specify those separately, and then the first two sets 
would only be used for the showers and for multiple interactions.
</li>

<p/>
<li>
If you want to perform some particle decays with an
external generator, you can call the <code>setDecayPtr(...)</code> 
method
<pre>
      pythia.setDecayPtr( decayHandlePtr, particles);
</pre>
where the <code>decayHandlePtr</code> derives from the 
<code>DecayHandler</code> base class and <code>particles</code> is a 
vector of particle codes to be handled
(<?php $filepath = $_GET["filepath"];
echo "<a href='ExternalDecays.php?filepath=".$filepath."' target='page'>";?>further instructions</a>). 
</li>

<p/>
<li>
If you want to use an external random number generator, 
you can call the <code>setRndmEnginePtr(...)</code> method
<pre>
      pythia.setRndmEnginePtr( rndmEnginePtr); 
</pre>
where <code>rndmEnginePtr</code> derives from the <code>RndmEngine</code> 
base class (<?php $filepath = $_GET["filepath"];
echo "<a href='RandomNumbers.php?filepath=".$filepath."' target='page'>";?>further instructions</a>). 
The <code>Pythia</code> default random number generator is perfectly good, 
so this is only intended for consistency in bigger frameworks.
</li>

<p/>
<li>
If you want to interrupt the evolution at various stages, 
to interrogate the event and possibly veto it, or you want to
reweight the cross section, you can use   
<pre>
      pythia.setUserHooksPtr( userHooksPtr); 
</pre>
where <code>userHooksPtr</code> derives from the <code>UserHooks</code> 
base class (<?php $filepath = $_GET["filepath"];
echo "<a href='UserHooks.php?filepath=".$filepath."' target='page'>";?>further instructions</a>).
</li>

<p/>
<li>
If you want to implement a cross section of your own, but still make use
of the built-in phase space selection machinery, you can use
<pre>
      pythia.setSigmaPtr( sigmaPtr);
</pre>
where <code>sigmaPtr</code> of type <code>SigmaProcess*</code> is an
instance of a class derived from one of the <code>Sigma1Process</code>,
<code>Sigma2Process</code> and  <code>Sigma3Process</code> base classes
(<?php $filepath = $_GET["filepath"];
echo "<a href='SemiInternalProcesses.php?filepath=".$filepath."' target='page'>";?>further instructions</a>). 
This call can be used repeatedly to hand in several different processes.
</li>

<p/>
<li>
If you are a real expert and want to replace the PYTHIA initial- and 
final-state showers, you can use
<pre>
      pythia.setShowerPtr( timesDecPtr, timesPtr, spacePtr);
</pre>
where <code>timesDecPtr</code> and <code>timesPtr</code>
derive from the <code>TimeShower</code> base class, and 
<code>spacePtr</code> from <code>SpaceShower</code> 
(<?php $filepath = $_GET["filepath"];
echo "<a href='ImplementNewShowers.php?filepath=".$filepath."' target='page'>";?>further instructions</a>). 
</li>

</ol>

<h3>The event loop</h3>

<ol>

<li>
Inside the event generation loop you generate the 
next event using the <code>next()</code> method,
<pre>
    pythia.next();
</pre>
This method takes no arguments; everything has already been specified. 
It does return a bool value, however, <code>false</code> when the
generation failed. This can be a "programmed death" when the
supply of input parton-level configurations on file is exhausted,
but also caused by a failure of <code>Pythia</code> to generate an event,
or that an event was generated but something strange was detected
in it. It makes sense to allow a few <code>false</code> 
values before a run is aborted, so long as the related faulty
events are skipped.
</li>  
 
<p/>
<li>
The generated event is now stored in the <code>event</code> 
object, of type <code>Event</code>, which is a public member of 
<code>pythia</code>. You therefore have access to all the tools described
on the pages under the "Study Output" header in the index. For instance, 
an event can be listed with 
<code>pythia.event.list()</code>, the identity of the <i>i</i>'th 
particle is given by <code>pythia.event[i].id()</code>, and so on.<br/> 
The hard process - roughly the information normally stored in the 
Les Houches Accord event record - is available as a second object, 
<code>process</code>, also of type <code>Event</code>.<br/> 
A third public object is <code>info</code>, which offers a set of
one-of-a kind pieces of information about the most recent event
(<?php $filepath = $_GET["filepath"];
echo "<a href='EventInformation.php?filepath=".$filepath."' target='page'>";?>further information</a>).
</li> 

</ol>

<h3>Finishing</h3>

<ol>

<li>At the end of the generation process, you can call
<pre>
    pythia.statistics(); 
</pre>
to get some run statistics, on cross sections and the number of errors 
and warnings encountered. With optional argument <code>true</code>
also further statistics is printed. Currently this means the number of
different subprocesses generated in the multiple-interactions
framework. 
</li> 

</ol>

</body>
</html>

<!-- Copyright (C) 2007 Torbjorn Sjostrand -->