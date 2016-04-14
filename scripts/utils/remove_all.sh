current_dir=$(pwd)
SCRIPT_PATH=`dirname "$0"`
cd  $SCRIPT_PATH
#read config
# project path current/../../
project_dir=$(dirname $(dirname $PWD))
source $project_dir/project.conf
./session_list.sh | $commons_path/scripts/applyRemoveAll.php remove
cd $current_dir
