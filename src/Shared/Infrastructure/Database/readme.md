# Описание таблиц базы данных

## crypto_currency

Таблица содержит информацию о криптовалютах, поддерживаемых в системе.

- `id` - int not null \
  Уникальный идентификатор криптовалюты.

- `symbol` - varchar(20) not null \
  Символьный код криптовалюты (например, BTC, ETH, USDT).

- `name` - varchar(255) not null \
  Полное название криптовалюты.

- `is_stablecoin` - boolean not null \
  Флаг, указывающий, является ли валюта стейблкоином.

- `decimals` - int not null \
  Количество знаков после запятой, используемых для отображения суммы в данной валюте.

- `logo_path` - varchar(255) default null \
  Путь к логотипу криптовалюты.

- `launch_year` - int default null \
  Год запуска криптовалюты.

- `project_url` - varchar(255) default null \
  URL официального сайта проекта.

- `explorer_url` - varchar(255) default null \
  URL обозревателя блокчейна для данной криптовалюты.

- `blockchain_type` - varchar(50) not null \
  Тип блокчейна (например, Bitcoin, Ethereum, Solana).

- `network` - varchar(50) default null \
  Сеть блокчейна (например, Mainnet, Testnet).

- `contract_address` - varchar(255) default null \
  Адрес смарт-контракта для токенов (для нативных криптовалют - null).

- `created_at` - timestamp(0) without time zone not null \
  Дата и время создания записи.

- `updated_at` - timestamp(0) without time zone default null \
  Дата и время последнего обновления записи.

## crypto_portfolio

Таблица хранит записи портфеля по конкретным криптоактивам.

- `id` - int not null \
  Уникальный идентификатор записи.

- `crypto_currency_id` - int not null UNIQUE (FK -> crypto_currency.id) \
  Ссылка на криптовалюту из таблицы crypto_currency. Поле уникально: одна запись портфеля на одну криптовалюту.

- `amount` - DECIMAL(36, 18) not null \
  Количество/объём актива.

- `average_price` - DECIMAL(36, 18) not null \
  Средняя цена покупки/входа по активу.

- `current_price` - DECIMAL(36, 18) not null \
  Текущая рыночная цена актива.

- `created_at` - timestamp(0) without time zone not null \
  Дата создания записи. Устанавливается на уровне сущности (Doctrine prePersist).

- `updated_at` - timestamp(0) without time zone default null \
  Дата последнего обновления записи. Обновляется на уровне сущности (Doctrine preUpdate).