// Authors: Chloé Gaudu <gaudu@uni-wuppertal.de>

// Keyword: analysis; hA collisions; cross-section

// Usage: ./main1010 Beams:idA Beams:idB mag_order_elab(projectile)
// can be run using ./run_pleiades_xsec_script on Pleiades

#include "Pythia8/HeavyIons.h"
#include "Pythia8/Pythia.h"
#include <cmath>
#include <iostream>
#include <fstream>
#include <map>
#include <vector>

template <typename K, typename V>
std::vector<K> getMapKeys(const std::map<K, V>& inputMap) {
    std::vector<K> keys;
    for (const auto& pair : inputMap) {
        keys.push_back(pair.first);
    }
    return keys;
}

using namespace Pythia8;

int main(int argc, char *argv[]) {

  if (argc<=1) {
    std::cout << "argc<=1" << '\n';
    if (argc==5) {
      std::cout << "argc==5" << '\n'; 
    }
    return 1; 
  }

  int nEvents = 10;
  int idA = atoi(argv[1]);
  int idB = atoi(argv[2]);

  std::map<std::string, std::string> idA_map = {
    {"2212", "p"}, {"-2212", "ap"}, {"2112", "n"}, {"-2112", "an"},
    {"111", "pi0"}, {"211", "pip"}, {"-211", "pim"},
    {"311", "k0"}, {"321", "kp"}, {"-321", "km"}, {"130", "k0l"}, {"310", "k0s"},
    {"3122", "lambda0"}, {"3212", "sigma0"}, {"3222", "sigmap"}, {"3112", "sigmam"}, {"3322", "xi0"}
    };
  std::map<std::string, std::string> idB_map = {
    {"2212", "1H"}, {"1000060120", "12C"}, {"1000070140", "14N"}, {"1000080160", "16O"}, {"1000180400", "40Ar"}
    };
  std::map<int, std::string> subprocess_map = {
    {101, "nd"}, {102, "el"}, {103, "XB"}, {104, "AX"}, {105, "XX"}, {106, "AXB"}
    };

  // logfile initialization
  const bool doLog = true;
  std::string out = "main1010_"+idA_map[std::string(argv[1])]+"_"+idB_map[std::string(argv[2])]+"_1e"+std::string(argv[3])+"_"+std::to_string(nEvents);
  ofstream logBuf;
  std::streambuf* oldCout;
  if(doLog) {
    oldCout = std::cout.rdbuf(logBuf.rdbuf());
    logBuf.open((out == "" ? "pythia.log" : out + ".log"));
  }
  
  // Pythia configuration
  Pythia pythia; 

  pythia.settings.mode("Beams:idA", idA);
  pythia.settings.mode("Beams:idB", idB);
  
  double e_lab = pow(10, std::stoi(argv[3])); // GeV
  double m_projectile = pythia.particleData.m0(idA); // GeV
  double m_target = pythia.particleData.m0(idB); // GeV
  double p_lab = sqrt(e_lab*e_lab - m_projectile*m_projectile);
  double calculated_eCM = sqrt((e_lab + m_target)*(e_lab + m_target) - p_lab*p_lab);
  std::cout << "calculated_eCM = " << calculated_eCM << '\n'; // GeV
  pythia.readString("Beams:frameType = 1");pythia.readString("Beams:frameType = 1");
  pythia.settings.parm("Beams:eCM", calculated_eCM);

  pythia.readString("SoftQCD:all = on");
  // use Angantyr for minimum-bias pp collisions
  //pythia.readString("HeavyIon:mode = 2");

  if (!pythia.init()) {
    std::cout << "Pythia failed to initialize." << std::endl;
    return -1;
  }

  std::ofstream xz{out + ".dat"};

  // loop over events
  for ( int iEvent = 0; iEvent < nEvents; ++iEvent ) { 
    if (!pythia.next()) continue;
  }
  
  std::vector<int> sigGenMKeys = getMapKeys(pythia.info.sigGenM);

  pythia.stat();

  if (!xz.is_open()) {
    std::cerr << "Error: failed to open file for writing." << std::endl;
    return 1; 
  }

  xz << "elab" << '\t' << "1e"+std::string(argv[3]) << '\n';
  xz << "proj_id" << '\t' << argv[1] << '\n';
  xz << "targ_id" << '\t' << argv[2] << '\n';
  xz << "sig_tot" << '\t' << pythia.info.sigmaGen(0) << '\n';
  xz << "err_tot" << '\t' << pythia.info.sigmaErr(0) << '\n';
  for (const auto& key : sigGenMKeys) {
        std::string label_sig = "sig_" + subprocess_map[key];
        std::string label_err = "err_" + subprocess_map[key];
        xz << label_sig << '\t' << pythia.info.sigmaGen(key) << '\n';
        xz << label_err << '\t' << pythia.info.sigmaErr(key) << '\n';
    }
  bool has102 = std::find(sigGenMKeys.begin(), sigGenMKeys.end(), 102) != sigGenMKeys.end();
  if (has102) {
  xz << "sig_inel" << '\t' << pythia.info.sigmaGen(0) - pythia.info.sigmaGen(102) << '\n';
  xz << "err_inel" << '\t' << sqrt(pow(pythia.info.sigmaErr(0),2) + pow(pythia.info.sigmaErr(102),2)) << '\n';
  }

  if (!xz) {
    std::cerr << "Error: failed to write data to the file." << std::endl;
    return 1; 
  }
  
  xz.close();
  std::cout << "Data written to output file successfully." << std::endl;
  
  // logfile output
  if (doLog) std::cout.rdbuf(oldCout);

  return 0;

}
