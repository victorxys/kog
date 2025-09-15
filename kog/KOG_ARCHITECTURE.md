# KOG 扑克局管理工具 - 架构与重构文档

本文档旨在分析 KOG 工具的现有架构、核心功能，并为未来的开发和重构提供一个清晰的路线图。

## 1. 核心理念与现状评估

### 品味评分
🟡 **凑合 (Passable)**

### 核心判断
✅ **值得做**

此工具的目标明确且实用：为特定群体提供一个轻量级的扑克牌局管理和记分工具。它没有陷入过度设计的泥潭，而是选择了一个现成的平台（WordPress）并迅速实现了核心功能。这种务实的态度值得肯定。

### 致命问题
代码的“品味”很差。所有东西都搅在一起——PHP逻辑、数据库查询、HTML结构、CSS样式和JavaScript脚本。尤其是 `kog_detail.php`，这个文件简直是个灾难，它一个人干了至少五个文件的活。这种写法让维护和扩展变得异常痛苦，任何微小的改动都可能引发雪崩式的bug。

我们的目标是在不破坏现有功能的前提下，逐步改善它的“品味”。

---

## 2. 技术架构 (Technical Architecture)

*   **框架 (Framework):**
    *   **WordPress:** 作为底层平台，主要利用其提供的数据库抽象函数（如`$wpdb->get_results`, `$wpdb->update`等）来简化数据库操作。

*   **数据层 (Data Layer):**
    *   数据库结构定义在 `kog_model.sql` 中。
    *   **数据模型核心:** `kog_kog_games` (牌局) 与 `kog_kog_details` (牌局详情) 之间清晰的“一对多”关系是整个系统的基石。
    *   **核心表:**
        *   `kog_kog_games`: 存储每一场牌局的基本信息（时间、地点、盲注、状态等）。
        *   `kog_kog_details`: 记录每位玩家在特定牌局中的详细数据（座位、起始/结束筹码、排名、最终盈亏等）。
        *   `kog_kog_user`: 玩家信息表。
        *   `kog_kog_rebuy`: 记录玩家的Rebuy（筹码增购）事件。
        *   `kog_kog_lucky`: 记录牌局中的特殊牌型（如四条、同花顺）以提供额外奖励。
        *   `kog_kog_gamegroup`: 用于将多场牌局按`memo`字段分组，进行财务统计。
    *   所有数据库操作的函数应集中在 `functions.php` 文件中。

*   **业务逻辑与表现层 (Business Logic & Presentation Layer):**
    *   **架构风格:** 典型的“面向文件”的程序集合，缺乏分层。每个 `kog_*.php` 文件都是一个独立的页面，同时包含了业务逻辑处理和HTML渲染。
    *   **UI/模板:**
        *   `kog_header.php` / `kog_footer.php`: 提供统一的页面头部和尾部，引入了 Argon Dashboard PRO 的UI样式和必要的JavaScript库。
    *   **风险点:** 逻辑和视图高度耦合，维护性差。此外，代码中大量直接使用 `$_REQUEST`，如果 `functions.php` 中没有做严格的全局过滤和验证，将存在巨大的安全隐患。

---

## 3. 核心功能 (Core Functionality)

#### 3.1. 牌局管理 (Game Management)
*   **牌局列表 (`index.php`):** 项目首页，展示所有历史牌局的列表。
*   **创建牌局 (`kog.php`):** 提供一个表单用于创建一场新的牌局，定义基本信息。
*   **牌局详情 (`kog_detail.php`):**
    *   显示牌局的座位、玩家、盲注结构和时间线。
    *   提供入口进行Rebuy和记录幸运牌型。
    *   允许通过表单提交每位玩家的最终筹码。
    *   **核心结算逻辑所在地：** 点击“Rank!”按钮后，该页面会计算每个玩家的最终排名、奖金、支付关系，并更新数据库。
    *   支持将牌局标记为“完成”(`status=2`)、删除(`status=0`)或“克隆”一场新牌局。
*   **多轮记分模式 (`kog_detail_1b1.php`):** `kog_detail.php` 的一个变体，支持在一场大牌局内进行多轮计分，最后汇总计算。

#### 3.2. 玩家操作 (Player Actions)
*   **Rebuy / 筹码增购 (`kog_rebuy.php`):**
    *   一个独立的表单页面，用于记录哪个玩家被谁“清空”并进行了Rebuy。
    *   记录Rebuy的筹码量和支付的现金。
*   **幸运牌型 (`kog_lucky.php`):**
    *   记录特殊大牌（如同花顺、四条等）的表单，包括玩家、牌型、奖励金额和当时的荷官。

#### 3.3. 统计与财务 (Statistics & Finance)
*   **系列局总结 (`kog_summary.php`):**
    *   按`memo`字段将多场牌局分组，展示一个系列局的时间线、每次的简报和最终的总输赢。
    *   提供为整个系列局添加公共成本（如场地费、餐饮费）的功能。
*   **成本分摊 (`kog_addcost.php`):**
    *   一个AJAX后台处理脚本，用于将`kog_summary.php`中添加的成本，按比例分配给系列局中的赢家。
*   **综合统计 (`stat.php`):**
    *   展示更宏观的玩家数据和历史统计。

---

## 4. 重构路线图 (Refactoring Roadmap)

这个项目能用，但离“好品味”还差很远。要让它能活得更久，必须做减法，消除复杂性。

### 第一步：数据与视图分离 (Separate Data from View)
*   **目标:** 停止在PHP里直接写HTML。
*   **行动:**
    1.  以 `kog_detail.php` 为起点，创建一个对应的模板文件，如 `templates/kog_detail_view.php`。
    2.  将 `kog_detail.php` 中的所有HTML代码移至模板文件。
    3.  `kog_detail.php` 只负责准备数据（查询数据库、计算排名等），并将所有需要显示的数据存入一个数组（例如 `$data`）。
    4.  在 `kog_detail.php` 的末尾，引入模板文件，并将 `$data` 数组传递给它。
    5.  在模板文件中，只使用简单的PHP语法（如 `foreach`, `echo`）来渲染 `$data` 中的数据。

### 第二步：抽象数据访问 (Abstract Data Access)
*   **目标:** 将SQL查询和业务计算逻辑从页面文件中剥离。
*   **行动:**
    1.  审查 `kog_detail.php` 和其他页面中的数据库查询和计算逻辑。
    2.  将这些逻辑封装成 `functions.php` 中更具体的函数。例如：
        *   `get_game_results($gid)`: 获取一场牌局的完整结果。
        *   `calculate_player_ranking($gid, $endings)`: 根据最终筹码计算玩家排名和奖金。
        *   `get_game_timeline($gid)`: 获取牌局的时间线事件（Rebuy, Lucky等）。
    3.  在页面文件中，只调用这些高级函数，而不是直接操作数据库或进行复杂计算。

### 第三步：统一入口，简化路由 (Unified Entry Point)
*   **目标:** 消除大量独立的PHP文件入口，改用单一入口控制器。
*   **行动:**
    1.  创建一个核心的 `kog.php` (或重用 `index.php`) 作为所有请求的入口。
    2.  通过URL参数来决定需要执行的操作，例如 `action`。
        *   `kog.php?action=show_game_list` -> 显示牌局列表
        *   `kog.php?action=show_game_detail&gid=123` -> 显示牌局详情
        *   `kog.php?action=submit_rebuy&gid=123` -> 处理Rebuy表单提交
    3.  使用 `switch` 语句或一个简单的路由函数来根据 `action` 参数调用对应的处理函数。
    4.  这将极大地简化项目结构，并为将来增加统一的权限验证、输入过滤等功能打下坚实基础。

**结论:** 先从 **第一步** 开始，把 `kog_detail.php` 这个最复杂的“巨兽”拆分成“数据处理”和“模板渲染”两部分。这是走向“好品味”的第一步，也是最关键的一步。
