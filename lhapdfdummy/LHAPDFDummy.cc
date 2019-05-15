// LHAPDFDummy.cc is a part of the PYTHIA event generator.
// Copyright (C) 2007 Torbjorn Sjostrand.
// PYTHIA is licenced under the GNU GPL version 2, see COPYING for details.
// Please respect the MCnet Guidelines, see GUIDELINES for details.

// Dummy routines to link when LHAPDF not linked.

extern "C" {

  void initpdfsetm_(int& nSet, const char*, int) {nSet = -1;}

  void initpdfsetbynamem_(int& nSet, const char*, int) {nSet = -1;}

  void initpdfm_(int& nSet, int&) {nSet = -1;}

  void evolvepdfm_(int& nSet, double&, double&, double*) {nSet = -1;}

}