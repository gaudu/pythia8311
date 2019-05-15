<html>
<head>
<title>Process Properties</title>
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

<form method='post' action='ProcessProperties.php'>

<h2>Process Properties</h2>

Here is collected some possibilities to affect the generation of 
all internally implemented processes in one go, or some coherent
subset thereof. Phase space cuts appear on a special page.

<h3>Incoming partons</h3>

<p/>
There is one useful degree of freedom to restrict the set of 
incoming flavours:
<br/><br/><table><tr><td><strong>SigmaProcess:nQuarkIn  </td><td></td><td> <input type="text" name="1" value="5" size="20"/>  &nbsp;&nbsp;(<code>default = <strong>5</strong></code>; <code>minimum = 0</code>; <code>maximum = 5</code>)</td></tr></table>
Number of allowed incoming quark flavours in the beams; a change 
to 4 would thus exclude <i>b</i> and <i>bbar</i> as incoming 
partons, etc.
</modeopen>

<h3>Coupling and scale choices</h3>

<code>SigmaProcess</code> is base class for all hard processes 
implemented in PYTHIA 8. For <i>2 -> 1</i> processes it 
should give <i>sigmaHat(sHat)</i>, for <i>2 -> 2</i> ones 
<i>d(sigmaHat(sHat, tHat))/d(tHat)</i>. The matrix-element
coding is also used by the multiple-interactions machinery,
but with a separate choice of <i>alpha_strong(M_Z^2)</i> value
and running, and separate scale choices. Also, in <i>2 -> 2</i> 
processes where resonances are produced, their couplings and thereby 
their Breit-Wigner shapes are always evaluated with the resonance mass 
as scale, irrespective of the choices below.

<p/>
The size of QCD cross sections is mainly determined by 
<br/><br/><table><tr><td><strong>SigmaProcess:alphaSvalue </td><td></td><td> <input type="text" name="2" value="0.1265" size="20"/>  &nbsp;&nbsp;(<code>default = <strong>0.1265</strong></code>; <code>minimum = 0.06</code>; <code>maximum = 0.25</code>)</td></tr></table>
The <i>alpha_strong</i> value at scale <i>M_Z^2</i>. 
  

<p/>
The actual value is then regulated by the running to the <i>Q^2</i> 
renormalization scale, at which <i>alpha_strong</i> is evaluated
<br/><br/><table><tr><td><strong>SigmaProcess:alphaSorder  </td><td>  &nbsp;&nbsp;(<code>default = <strong>1</strong></code>; <code>minimum = 0</code>; <code>maximum = 2</code>)</td></tr></table>
<modepick name="SigmaProcess:alphaSorder" default="1" min="0" max="2">
Order at which <ei>alpha_strong</ei> runs,
<br/>
<input type="radio" name="3" value="0"><strong>0 </strong>: zeroth order, i.e. <ei>alpha_strong</ei> is kept fixed.<br/>
<input type="radio" name="3" value="1" checked="checked"><strong>1 </strong>: first order, which is the normal value.<br/>
<input type="radio" name="3" value="2"><strong>2 </strong>: second order. Since other parts of the code do not go to second order there is no strong reason to use this option, but there is also nothing wrong with it.<br/>
</modepick>

<p/>
QED interactions are regulated by the <i>alpha_electromagnetic</i>
value at the <i>Q^2</i> renormalization scale of an interaction. 
<br/><br/><table><tr><td><strong>SigmaProcess:alphaEMorder  </td><td>  &nbsp;&nbsp;(<code>default = <strong>1</strong></code>; <code>minimum = -1</code>; <code>maximum = 1</code>)</td></tr></table>
<modepick name="SigmaProcess:alphaEMorder" default="1" min="-1" max="1">
The running of <ei>alpha_em</ei> used in hard processes.
<br/>
<input type="radio" name="4" value="1" checked="checked"><strong>1 </strong>: first-order running, constrained to agree with<code>StandardModel:alphaEMmZ</code> at the <ei>Z^0</ei> mass.<br/>
<input type="radio" name="4" value="0"><strong>0 </strong>: zeroth order, i.e. <ei>alpha_em</ei> is kept fixed at its value at vanishing momentum transfer.<br/>
<input type="radio" name="4" value="-1"><strong>-1 </strong>: zeroth order, i.e. <ei>alpha_em</ei> is kept fixed, but at <code>StandardModel:alphaEMmZ</code>, i.e. its valueat the <ei>Z^0</ei> mass.<br/>
</modepick>

<p/>
The <i>Q^2</i> renormalization scale can be chosen among a few different
alternatives, separately for <i>2 -> 1</i>, <i>2 -> 2</i> and two 
different kinds of <i>2 -> 3</i> processes. In addition a common
multiplicative factor may be imposed.
 
<br/><br/><table><tr><td><strong>SigmaProcess:renormScale1  </td><td>  &nbsp;&nbsp;(<code>default = <strong>1</strong></code>; <code>minimum = 1</code>; <code>maximum = 2</code>)</td></tr></table>
<modepick name="SigmaProcess:renormScale1" default="1" min="1" max="2">
The <ei>Q^2</ei> renormalization scale for <ei>2 -> 1</ei> processes.
The same options also apply for those <ei>2 -> 2</ei> and <ei>2 -> 3</ei>
processes that have been specially marked as proceeding only through 
an <ei>s</ei>-channel resonance, by the <code>isSChannel()</code> virtual 
method of <code>SigmaProcess</code>.
<br/>
<input type="radio" name="5" value="1" checked="checked"><strong>1 </strong>: the squared invariant mass, i.e. <ei>sHat</ei>.<br/>
<input type="radio" name="5" value="2"><strong>2 </strong>: fix scale set in <code>SigmaProcess:renormFixScale</code> below.<br/>
</modepick>
  
<br/><br/><table><tr><td><strong>SigmaProcess:renormScale2  </td><td>  &nbsp;&nbsp;(<code>default = <strong>2</strong></code>; <code>minimum = 1</code>; <code>maximum = 5</code>)</td></tr></table>
<modepick name="SigmaProcess:renormScale2" default="2" min="1" max="5">
The <ei>Q^2</ei> renormalization scale for <ei>2 -> 2</ei> processes.
<br/>
<input type="radio" name="6" value="1"><strong>1 </strong>: the smaller of the squared transverse masses of the twooutgoing particles, i.e. <ei>min(mT_3^2, mT_4^2) = pT^2 + min(m_3^2, m_4^2)</ei>.<br/>
<input type="radio" name="6" value="2" checked="checked"><strong>2 </strong>: the geometric mean of the squared transverse masses of the two outgoing particles, i.e. <ei>mT_3 * mT_4 = sqrt((pT^2 + m_3^2) * (pT^2 + m_4^2))</ei>.<br/>
<input type="radio" name="6" value="3"><strong>3 </strong>: the arithmetic mean of the squared transverse masses of the two outgoing particles, i.e. <ei>(mT_3^2 + mT_4^2) / 2 = pT^2 + 0.5 * (m_3^2 + m_4^2)</ei>. Useful for comparisons with PYTHIA 6, where this is the default.<br/>
<input type="radio" name="6" value="4"><strong>4 </strong>: squared invariant mass of the system, i.e. <ei>sHat</ei>. Useful for processes dominated by <ei>s</ei>-channel exchange. <br/>
<input type="radio" name="6" value="5"><strong>5 </strong>: fix scale set in <code>SigmaProcess:renormFixScale</code> below.<br/>
</modepick>
  
<br/><br/><table><tr><td><strong>SigmaProcess:renormScale3  </td><td>  &nbsp;&nbsp;(<code>default = <strong>3</strong></code>; <code>minimum = 1</code>; <code>maximum = 6</code>)</td></tr></table>
<modepick name="SigmaProcess:renormScale3" default="3" min="1" max="6">
The <ei>Q^2</ei> renormalization scale for "normal" <ei>2 -> 3</ei> 
processes, i.e excepting the vector-boson-fusion processes below.
Here it is assumed that particle masses in the final state either match
or are heavier than that of any <ei>t</ei>-channel propagator particle.
(Currently only <ei>g g / q qbar -> H^0 Q Qbar</ei> processes are 
implemented, where the "match" criterion holds.) 
<br/>
<input type="radio" name="7" value="1"><strong>1 </strong>: the smaller of the squared transverse masses of the threeoutgoing particles, i.e. min(mT_3^2, mT_4^2, mT_5^2).<br/>
<input type="radio" name="7" value="2"><strong>2 </strong>: the geometric mean of the two smallest squared transverse masses of the three outgoing particles, i.e. <ei>sqrt( mT_3^2 * mT_4^2 * mT_5^2 / max(mT_3^2, mT_4^2, mT_5^2) )</ei>.<br/>
<input type="radio" name="7" value="3" checked="checked"><strong>3 </strong>: the geometric mean of the squared transverse masses of the three outgoing particles, i.e. <ei>(mT_3^2 * mT_4^2 * mT_5^2)^(1/3)</ei>.<br/>
<input type="radio" name="7" value="4"><strong>4 </strong>: the arithmetic mean of the squared transverse masses of the three outgoing particles, i.e. <ei>(mT_3^2 + mT_4^2 + mT_5^2)/3</ei>.<br/>
<input type="radio" name="7" value="5"><strong>5 </strong>: squared invariant mass of the system, i.e. <ei>sHat</ei>.<br/>
<input type="radio" name="7" value="6"><strong>6 </strong>: fix scale set in <code>SigmaProcess:renormFixScale</code> below.<br/>
</modepick> 
 
<br/><br/><table><tr><td><strong>SigmaProcess:renormScale3VV  </td><td>  &nbsp;&nbsp;(<code>default = <strong>3</strong></code>; <code>minimum = 1</code>; <code>maximum = 6</code>)</td></tr></table>
<modepick name="SigmaProcess:renormScale3VV" default="3" min="1" max="6">
The <ei>Q^2</ei> renormalization scale for <ei>2 -> 3</ei> 
vector-boson-fusion processes, i.e. <ei>f_1 f_2 -> H^0 f_3 f_4</ei>
with <ei>Z^0</ei> or <ei>W^+-</ei>  <ei>t</ei>-channel propagators. 
Here the transverse masses of the outgoing fermions do not reflect the 
virtualities of the exchanged bosons. A better estimate is obtained 
by replacing the final-state fermion masses by the vector-boson ones
in the definition of transverse masses. We denote these combinations 
<ei>mT_Vi^2 = m_V^2 + pT_i^2</ei>. 
<br/>
<input type="radio" name="8" value="1"><strong>1 </strong>: the squared mass <ei>m_V^2</ei> of the exchangedvector boson.<br/>
<input type="radio" name="8" value="2"><strong>2 </strong>: the geometric mean of the two propagator virtualityestimates, i.e. <ei>sqrt(mT_V3^2 * mT_V4^2)</ei>.<br/>
<input type="radio" name="8" value="3" checked="checked"><strong>3 </strong>: the geometric mean of the three relevant squared transverse masses, i.e. <ei>(mT_V3^2 * mT_V4^2 * mT_H^2)^(1/3)</ei>.<br/>
<input type="radio" name="8" value="4"><strong>4 </strong>: the arithmetic mean of the three relevant squared transverse masses, i.e. <ei>(mT_V3^2 + mT_V4^2 + mT_H^2)/3</ei>.<br/>
<input type="radio" name="8" value="5"><strong>5 </strong>: squared invariant mass of the system, i.e. <ei>sHat</ei>.<br/>
<input type="radio" name="8" value="6"><strong>6 </strong>: fix scale set in <code>SigmaProcess:renormFixScale</code> below.<br/>
</modepick>

<br/><br/><table><tr><td><strong>SigmaProcess:renormMultFac </td><td></td><td> <input type="text" name="9" value="1." size="20"/>  &nbsp;&nbsp;(<code>default = <strong>1.</strong></code>; <code>minimum = 0.1</code>; <code>maximum = 10.</code>)</td></tr></table>
The <i>Q^2</i> renormalization scale for <i>2 -> 1</i>,
<i>2 -> 2</i> and <i>2 -> 3</i> processes is multiplied by 
this factor relative to the scale described above (except for the options 
with a fix scale). Should be use sparingly for <i>2 -> 1</i> processes. 
  

<br/><br/><table><tr><td><strong>SigmaProcess:renormFixScale </td><td></td><td> <input type="text" name="10" value="10000." size="20"/>  &nbsp;&nbsp;(<code>default = <strong>10000.</strong></code>; <code>minimum = 1.</code>)</td></tr></table>
A fix <i>Q^2</i> value used as renormalization scale for <i>2 -> 1</i>,
<i>2 -> 2</i> and <i>2 -> 3</i> processes in some of the options above.
  

<p/>
Corresponding options exist for the <i>Q^2</i> factorization scale
used as argument in PDF's. Again there is a choice of form for  
<i>2 -> 1</i>, <i>2 -> 2</i> and <i>2 -> 3</i> processes separately. 
For simplicity we have let the numbering of options agree, for each event 
class separately, between normalization and factorization scales, and the 
description has therefore been slightly shortened. The default values are 
<b>not</b> necessarily the same, however.
 
<br/><br/><table><tr><td><strong>SigmaProcess:factorScale1  </td><td>  &nbsp;&nbsp;(<code>default = <strong>1</strong></code>; <code>minimum = 1</code>; <code>maximum = 2</code>)</td></tr></table>
<modepick name="SigmaProcess:factorScale1" default="1" min="1" max="2">
The <ei>Q^2</ei> factorization scale for <ei>2 -> 1</ei> processes.
The same options also apply for those <ei>2 -> 2</ei> and <ei>2 -> 3</ei>
processes that have been specially marked as proceeding only through 
an <ei>s</ei>-channel resonance.
<br/>
<input type="radio" name="11" value="1" checked="checked"><strong>1 </strong>: the squared invariant mass, i.e. <ei>sHat</ei>.<br/>
<input type="radio" name="11" value="2"><strong>2 </strong>: fix scale set in <code>SigmaProcess:factorFixScale</code> below.<br/>
</modepick>

<br/><br/><table><tr><td><strong>SigmaProcess:factorScale2  </td><td>  &nbsp;&nbsp;(<code>default = <strong>1</strong></code>; <code>minimum = 1</code>; <code>maximum = 5</code>)</td></tr></table>
<modepick name="SigmaProcess:factorScale2" default="1" min="1" max="5">
The <ei>Q^2</ei> factorization scale for <ei>2 -> 2</ei> processes.
<br/>
<input type="radio" name="12" value="1" checked="checked"><strong>1 </strong>: the smaller of the squared transverse masses of the twooutgoing particles.<br/>
<input type="radio" name="12" value="2"><strong>2 </strong>: the geometric mean of the squared transverse masses of the two outgoing particles.<br/>
<input type="radio" name="12" value="3"><strong>3 </strong>: the arithmetic mean of the squared transverse masses of the two outgoing particles. Useful for comparisons with PYTHIA 6, where this is the default.<br/>
<input type="radio" name="12" value="4"><strong>4 </strong>: squared invariant mass of the system, i.e. <ei>sHat</ei>. Useful for processes dominated by <ei>s</ei>-channel exchange. <br/>
<input type="radio" name="12" value="5"><strong>5 </strong>: fix scale set in <code>SigmaProcess:factorFixScale</code> below.<br/>
</modepick>
  
<br/><br/><table><tr><td><strong>SigmaProcess:factorScale3  </td><td>  &nbsp;&nbsp;(<code>default = <strong>2</strong></code>; <code>minimum = 1</code>; <code>maximum = 6</code>)</td></tr></table>
<modepick name="SigmaProcess:factorScale3" default="2" min="1" max="6">
The <ei>Q^2</ei> factorization scale for "normal" <ei>2 -> 3</ei> 
processes, i.e excepting the vector-boson-fusion processes below.
<br/>
<input type="radio" name="13" value="1"><strong>1 </strong>: the smaller of the squared transverse masses of the threeoutgoing particles).<br/>
<input type="radio" name="13" value="2" checked="checked"><strong>2 </strong>: the geometric mean of the two smallest squared transverse masses of the three outgoing particles.<br/>
<input type="radio" name="13" value="3"><strong>3 </strong>: the geometric mean of the squared transverse masses of the three outgoing particles.<br/>
<input type="radio" name="13" value="4"><strong>4 </strong>: the arithmetic mean of the squared transverse masses of the three outgoing particles.<br/>
<input type="radio" name="13" value="5"><strong>5 </strong>: squared invariant mass of the system, i.e. <ei>sHat</ei>.<br/>
<input type="radio" name="13" value="6"><strong>6 </strong>: fix scale set in <code>SigmaProcess:factorFixScale</code> below.<br/>
</modepick> 
 
<br/><br/><table><tr><td><strong>SigmaProcess:factorScale3VV  </td><td>  &nbsp;&nbsp;(<code>default = <strong>2</strong></code>; <code>minimum = 1</code>; <code>maximum = 6</code>)</td></tr></table>
<modepick name="SigmaProcess:factorScale3VV" default="2" min="1" max="6">
The <ei>Q^2</ei> factorization scale for <ei>2 -> 3</ei> 
vector-boson-fusion processes, i.e. <ei>f_1 f_2 -> H^0 f_3 f_4</ei>
with <ei>Z^0</ei> or <ei>W^+-</ei>  <ei>t</ei>-channel propagators. 
Here we again introduce the combinations <ei>mT_Vi^2 = m_V^2 + pT_i^2</ei>
as replacements for the normal squared transverse masses of the two 
outgoing quarks.
<br/>
<input type="radio" name="14" value="1"><strong>1 </strong>: the squared mass <ei>m_V^2</ei> of the exchangedvector boson.<br/>
<input type="radio" name="14" value="2" checked="checked"><strong>2 </strong>: the geometric mean of the two propagator virtualityestimates.<br/>
<input type="radio" name="14" value="3"><strong>3 </strong>: the geometric mean of the three relevant squared transverse masses.<br/>
<input type="radio" name="14" value="4"><strong>4 </strong>: the arithmetic mean of the three relevant squared transverse masses.<br/>
<input type="radio" name="14" value="5"><strong>5 </strong>: squared invariant mass of the system, i.e. <ei>sHat</ei>.<br/>
<input type="radio" name="14" value="6"><strong>6 </strong>: fix scale set in <code>SigmaProcess:factorFixScale</code> below.<br/>
</modepick>

<br/><br/><table><tr><td><strong>SigmaProcess:factorMultFac </td><td></td><td> <input type="text" name="15" value="1." size="20"/>  &nbsp;&nbsp;(<code>default = <strong>1.</strong></code>; <code>minimum = 0.1</code>; <code>maximum = 10.</code>)</td></tr></table>
The <i>Q^2</i> factorization scale for <i>2 -> 1</i>,
<i>2 -> 2</i> and <i>2 -> 3</i> processes is multiplied by 
this factor relative to the scale described above (except for the options 
with a fix scale). Should be use sparingly for <i>2 -> 1</i> processes. 
  

<br/><br/><table><tr><td><strong>SigmaProcess:factorFixScale </td><td></td><td> <input type="text" name="16" value="10000." size="20"/>  &nbsp;&nbsp;(<code>default = <strong>10000.</strong></code>; <code>minimum = 1.</code>)</td></tr></table>
A fix <i>Q^2</i> value used as factorization scale for <i>2 -> 1</i>,
<i>2 -> 2</i> and <i>2 -> 3</i> processes in some of the options above.
  


<h3>Special cross sections</h3>

Here settings that affect some special group of processes, but not all.

<br/><br/><table><tr><td><strong>SigmaProcess:nQuarkNew  </td><td></td><td> <input type="text" name="17" value="3" size="20"/>  &nbsp;&nbsp;(<code>default = <strong>3</strong></code>; <code>minimum = 0</code>; <code>maximum = 5</code>)</td></tr></table>
Number of allowed outgoing new quark flavours in 
<i>q qbar -> q' qbar'</i> and <i>g g-> q qbar</i> processes, 
where quarks are treated as massless in the matrix-element expressions 
(but correctly in the phase space). It is thus assumed that <i>c cbar</i> 
and <i>b bbar</i> are added separately with masses taken into account. 
A change to 4 would also include <i>c cbar</i> in the massless 
approximation, etc. 
</modeopen>

<br/><br/><table><tr><td><strong>SigmaProcess:nQuarkLoop  </td><td></td><td> <input type="text" name="18" value="5" size="20"/>  &nbsp;&nbsp;(<code>default = <strong>5</strong></code>; <code>minimum = 3</code>; <code>maximum = 6</code>)</td></tr></table>
Number of quark flavours included in the box graphs resposible for 
<i>g g -> g gamma</i> and <i>g g-> gamma gamma</i> processes.
Owing to the complexity if the massive expressions, quarks are treated 
as massless. The default value should be applicable in the range of 
transverse momenta above the <i>b</i> mass but below the <i>t</i> one.
</modeopen>

<br/><br/><table><tr><td><strong>SigmaProcess:gmZmode  </td><td>  &nbsp;&nbsp;(<code>default = <strong>0</strong></code>; <code>minimum = 0</code>; <code>maximum = 2</code>)</td></tr></table>
<modepick name="SigmaProcess:gmZmode" default="0" min="0" max="2">
Choice of full <ei>gamma^*/Z^0</ei> structure or not in relevant 
processes.
<br/>
<input type="radio" name="19" value="0" checked="checked"><strong>0 </strong>: full <ei>gamma^*/Z^0</ei> structure,with interference included.<br/>
<input type="radio" name="19" value="1"><strong>1 </strong>: only pure <ei>gamma^*</ei> contribution.<br/>
<input type="radio" name="19" value="2"><strong>2 </strong>: only pure <ei>Z^0</ei> contribution.<br/>
</modepick>

<input type="hidden" name="saved" value="1"/>

<?php
echo "<input type='hidden' name='filepath' value='".$_GET["filepath"]."'/>"?>

<table width="100%"><tr><td align="right"><input type="submit" value="Save Settings" /></td></tr></table>
</form>

<?php

if($_POST["saved"] == 1)
{
$filepath = $_POST["filepath"];
$handle = fopen($filepath, 'a');

if($_POST["1"] != "5")
{
$data = "SigmaProcess:nQuarkIn = ".$_POST["1"]."\n";
fwrite($handle,$data);
}
if($_POST["2"] != "0.1265")
{
$data = "SigmaProcess:alphaSvalue = ".$_POST["2"]."\n";
fwrite($handle,$data);
}
if($_POST["3"] != "1")
{
$data = "SigmaProcess:alphaSorder = ".$_POST["3"]."\n";
fwrite($handle,$data);
}
if($_POST["4"] != "1")
{
$data = "SigmaProcess:alphaEMorder = ".$_POST["4"]."\n";
fwrite($handle,$data);
}
if($_POST["5"] != "1")
{
$data = "SigmaProcess:renormScale1 = ".$_POST["5"]."\n";
fwrite($handle,$data);
}
if($_POST["6"] != "2")
{
$data = "SigmaProcess:renormScale2 = ".$_POST["6"]."\n";
fwrite($handle,$data);
}
if($_POST["7"] != "3")
{
$data = "SigmaProcess:renormScale3 = ".$_POST["7"]."\n";
fwrite($handle,$data);
}
if($_POST["8"] != "3")
{
$data = "SigmaProcess:renormScale3VV = ".$_POST["8"]."\n";
fwrite($handle,$data);
}
if($_POST["9"] != "1.")
{
$data = "SigmaProcess:renormMultFac = ".$_POST["9"]."\n";
fwrite($handle,$data);
}
if($_POST["10"] != "10000.")
{
$data = "SigmaProcess:renormFixScale = ".$_POST["10"]."\n";
fwrite($handle,$data);
}
if($_POST["11"] != "1")
{
$data = "SigmaProcess:factorScale1 = ".$_POST["11"]."\n";
fwrite($handle,$data);
}
if($_POST["12"] != "1")
{
$data = "SigmaProcess:factorScale2 = ".$_POST["12"]."\n";
fwrite($handle,$data);
}
if($_POST["13"] != "2")
{
$data = "SigmaProcess:factorScale3 = ".$_POST["13"]."\n";
fwrite($handle,$data);
}
if($_POST["14"] != "2")
{
$data = "SigmaProcess:factorScale3VV = ".$_POST["14"]."\n";
fwrite($handle,$data);
}
if($_POST["15"] != "1.")
{
$data = "SigmaProcess:factorMultFac = ".$_POST["15"]."\n";
fwrite($handle,$data);
}
if($_POST["16"] != "10000.")
{
$data = "SigmaProcess:factorFixScale = ".$_POST["16"]."\n";
fwrite($handle,$data);
}
if($_POST["17"] != "3")
{
$data = "SigmaProcess:nQuarkNew = ".$_POST["17"]."\n";
fwrite($handle,$data);
}
if($_POST["18"] != "5")
{
$data = "SigmaProcess:nQuarkLoop = ".$_POST["18"]."\n";
fwrite($handle,$data);
}
if($_POST["19"] != "0")
{
$data = "SigmaProcess:gmZmode = ".$_POST["19"]."\n";
fwrite($handle,$data);
}
fclose($handle);
}

?>
</body>
</html>

<!-- Copyright (C) 2007 Torbjorn Sjostrand -->