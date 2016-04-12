current_dir=$(pwd)
SCRIPT_PATH=`dirname "$0"`
cd  $SCRIPT_PATH
#read config
source ../../project.conf
./session_list.sh | $commons_path/scripts/applyRemoveAll.php remove
cd $current_dir
