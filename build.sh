#!/bin/bash

# 如果任何命令失败，立即停止脚本
set -e

# --- 配置 ---
# 你的 Docker Hub 用户名/仓库名
IMAGE_NAME="victorxys/kog"
# 目标服务器的硬件架构
PLATFORM="linux/amd64"

# --- 1. 从 Docker Hub 获取最新版本 ---
echo "🔎 正在从 Docker Hub 获取 [${IMAGE_NAME}] 的版本标签..."

# 使用 curl 请求 Docker Hub API，通过一系列管道命令筛选出版本号最高的标签
LATEST_VERSION=$(curl -s "https://hub.docker.com/v2/repositories/${IMAGE_NAME}/tags/?page_size=100" | \
                 grep -o '"name": "v[0-9\.]*"' | \
                 grep -o 'v[0-9\.]*' | \
                 sort -V | \
                 tail -n 1)

if [ -z "$LATEST_VERSION" ]; then
  echo "⚠️ 未找到历史版本，将从 v1.0.0 开始。"
  NEW_VERSION="v1.0.0"
else
  echo "✅ 已找到最新版本: ${LATEST_VERSION}"
  
  # --- 2. 递增版本号 ---
  # 使用 awk 来递增修订号 (最后一段数字)
  NEW_VERSION=$(echo "$LATEST_VERSION" | awk -F. -v OFS=. '{ 
    sub(/^v/, "", $1);
    $NF = $NF + 1;
    print "v" $0;
  }')
fi

echo "🚀 即将构建的新版本为: ${NEW_VERSION}"

# --- 3. 构建、标记并推送镜像 (使用旧版构建器) ---
echo "🛠️ 正在为平台 [${PLATFORM}] 构建镜像 (使用旧版构建器)..."
echo "   镜像: ${IMAGE_NAME}:${NEW_VERSION}"

# 设置 DOCKER_BUILDKIT=0 环境变量以禁用 BuildKit 并构建
DOCKER_BUILDKIT=0 docker build \
  --platform "$PLATFORM" \
  -t "${IMAGE_NAME}:${NEW_VERSION}" \
  .

echo "🏷️ 正在标记 'latest' 标签..."
docker tag "${IMAGE_NAME}:${NEW_VERSION}" "${IMAGE_NAME}:latest"

echo "🚀 正在推送版本标签 (${NEW_VERSION}) 到 Docker Hub..."
docker push "${IMAGE_NAME}:${NEW_VERSION}"

echo "🚀 正在推送 'latest' 标签到 Docker Hub..."
docker push "${IMAGE_NAME}:latest"



echo ""
echo "✅ Successfully built and pushed ${IMAGE_NAME}:${NEW_VERSION} 和 ${IMAGE_NAME}:latest."
echo ""
echo "--------------------------------------------------"
echo "下一步：在你的生产服务器上执行以下操作"
echo "--------------------------------------------------"
echo ""
echo "1. SSH 登录到你的服务器。"

echo ""
echo "2. 编辑 docker-compose.yml 文件，将 image 更新为新版本:"
echo "   image: ${IMAGE_NAME}:${NEW_VERSION}"

echo ""
echo "3. 拉取新镜像并重启服务:"
echo "   docker-compose pull && docker-compose up -d"

echo ""
echo "--------------------------------------------------"
