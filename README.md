# Laravel 11 API 示例项目

本项目是一个基于Laravel 11构建的API示例，集成了加密解密、多语言支持、异步任务队列、API身份验证、请求频率限制、请求重复规避以及详细的请求日志记录等功能，旨在提供一个高效、安全且易于扩展的API开发框架。

## 技术栈

- **后端框架**: Laravel 11
- **数据库**: MySQL
- **API认证**: Sanctum Token机制
- **加密解密**: AES + RSA
- **语言包**: Laravel多语言支持
- **异步队列**: Redis
- **请求频率限制**: Laravel中间件实现
- **请求重复规避**: 基于唯一标识和缓存机制
- **请求日志**: Laravel日志系统
- **异常处理**: 全局异常捕获
- **API状态码**: API自定状态码

## 特性

- **安全认证**: 实现了Sanctum认证，保证API接口的安全调用。
- **多语言支持**: 用户可以根据偏好选择界面语言。
- **高性能异步处理**: 利用队列处理耗时任务，提高系统响应速度。
- **防止恶意请求**: 限制API调用频率，避免服务被滥用。
- **避免重复提交**: 通过生成请求唯一标识，有效规避用户误操作导致的重复请求。
- **详尽日志**: 记录每一次API请求的详细信息，便于问题追踪和分析。
- **异常处理**: 对所有异常进行捕获处理，JSON输出
- **API状态码**: 对所有接口加入自定义状态码，规范API开发

## 快速开始

### 环境要求

- PHP >= 8.2
- Composer
- MySQL
- Redis

### 安装步骤

1. **克隆项目**

   `git clone https://github.com/zhangqingxi/Laravel11Api.git`
   
2. **安装依赖**

   进入项目目录并运行：
   
	`composer install`
	
3. **配置环境**

   - 复制 `.env.example` 为 `.env` 并配置数据库连接、队列等信息。
   - 运行 `php artisan key:generate` 生成应用密钥。

4. **数据库迁移**

	`php artisan migrate`
	
5. **启动服务**

   开发环境下，可以使用 Laravel 内置服务器：

	`php artisan serve`

	

   