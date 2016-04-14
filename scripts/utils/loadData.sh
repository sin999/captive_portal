#read config
# project path current/../../
project_dir=$(dirname $PWD)
source $project_dir/project.conf

./session_list.sh | $commons_path/scripts/loadData.php $1