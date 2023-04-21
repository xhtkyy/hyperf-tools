#!/bin/bash

#define normal
in_proto_path='../protos'
grpc_path='./app/Grpc'

while getopts i:p:f: OPT; do
  case ${OPT} in
    i) in_proto_path=${OPTARG}
       ;;
    p) grpc_path=${OPTARG}
       ;;
    f) files=${OPTARG}
       ;;
    \?)
       printf "[Usage] `compose protoc '+%F %T'` -i <IN_PROTO_PATH> -p <GRPC_PATH> -f <PROTO_FILES> \n" >&2
       exit 1
  esac
done

# fix app
if [[ $grpc_path =~ "/app/" ]]
  then
    mv app App
    protoc --php_out=./ --grpc_out=./ --plugin=protoc-gen-grpc=/usr/local/lib/grpc_php_plugin -I="$in_proto_path" $files
    mv App app
  else
    protoc --php_out=./ --grpc_out=./ --plugin=protoc-gen-grpc=/usr/local/lib/grpc_php_plugin -I="$in_proto_path" $files
fi

#replace
replace(){
 for file in `ls $1`
 do
      if [ -d $1"/"$file ]
    then
        replace $1"/"$file
    else
        if [[ $file =~ "Client.php" ]]
      then
        exist=`grep "__construct" "$1/$file"`
        if [ ! -z "$exist" ];then
          sed -i 10,17d $1"/"$file
          sed -i 's/\\Grpc\\BaseStub/\\Xhtkyy\\HyperfTools\\GrpcClient\\BaseGrpcClient/g' $1"/"$file
          sed -i 's/\\Grpc\\UnaryCall/array/g' $1"/"$file
          echo "replace $1/$file finish!"
        fi
      fi
    fi
 done
}

replace $grpc_path .