
├── admin/                   # 后台管理界面
│   ├── index.php           # 后台主界面
│   ├── users.php           # 用户管理
│   ├── conversations.php   # 对话监控
│   ├── apis.php            # API密钥管理
│   └── stats.php           # API使用统计
├── includes/               # 公共函数库
│   ├── config.php          # 数据库/API主配置
│   ├── auth.php            # 认证相关函数
│   ├── database.php        # 数据库操作类
│   ├── api_handler.php     # AI接口统一处理器
│   └── utils.php           # 通用工具函数
├── static/                 # 静态资源
│   ├── css/
│   │   └── style.css       # 全局样式
│   ├── js/
│   │   ├── chat.js         # 聊天交互逻辑
│   │   └── clipboard.js    # 复制功能实现
│   └── uploads/            # 用户上传文件存储
├── pages/                  # 用户界面
│   ├── login.php           # 登录/注册页
│   ├── chat.php            # 主聊天界面
│   └── profile.php         # 用户信息页
├── cron/                   # 定时任务
│   └── api_stats.php       # API统计聚合任务
└── index.php               # 网站入口