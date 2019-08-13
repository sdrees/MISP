#### Install mail to misp
--------------------

```bash
# <snippet-begin 5_mail_to_misp.sh>
# Main mail2misp install function
mail2misp () {
  debug "Installing Mail2${LBLUE}MISP${NC}"
  cd /usr/local/src/
  sudo apt-get install cmake libcaca-dev liblua5.3-dev -y
  $SUDO_USER git clone https://github.com/MISP/mail_to_misp.git
  $SUDO_USER git clone git://github.com/stricaud/faup.git faup
  $SUDO_USER git clone git://github.com/stricaud/gtcaca.git gtcaca
  sudo chown -R ${MISP_USER}:${MISP_USER} faup mail_to_misp gtcaca
  cd gtcaca
  $SUDO_USER mkdir -p build
  cd build
  $SUDO_USER cmake .. && $SUDO_USER make
  sudo make install
  cd ../../faup
  $SUDO_USER mkdir -p build
  cd build
  $SUDO_USER cmake .. && $SUDO_USER make
  sudo make install
  sudo ldconfig
  cd ../../mail_to_misp
  $SUDO_USER virtualenv -p python3 venv
  $SUDO_USER ./venv/bin/pip install https://github.com/lief-project/packages/raw/lief-master-latest/pylief-0.9.0.dev.zip
  $SUDO_USER ./venv/bin/pip install -r requirements.txt
  $SUDO_USER cp mail_to_misp_config.py-example mail_to_misp_config.py
  ##$SUDO cp mail_to_misp_config.py-example mail_to_misp_config.py
  $SUDO_USER sed -i "s/^misp_url\ =\ 'YOUR_MISP_URL'/misp_url\ =\ 'https:\/\/localhost'/g" /usr/local/src/mail_to_misp/mail_to_misp_config.py
  $SUDO_USER sed -i "s/^misp_key\ =\ 'YOUR_KEY_HERE'/misp_key\ =\ '${AUTH_KEY}'/g" /usr/local/src/mail_to_misp/mail_to_misp_config.py
}
# <snippet-end 5_mail_to_misp.sh>
```
