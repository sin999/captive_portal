current_dir=$(pwd)
SCRIPT_PATH=`dirname "$0"`
cd  $SCRIPT_PATH
#read config
project_dir=$(dirname $PWD)
source $project_dir/project.conf
./session_list.sh | $commons_path/scripts/applyRemoveAll.php apply 
cd $current_dir
