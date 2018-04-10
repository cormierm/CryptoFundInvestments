import logging
import requests
import datetime
import time
import pymysql
import json


LOG_FILENAME = 'output.log'
CONFIG_FILENAME = 'config.json'
BASE_API_URL = 'https://api.coinmarketcap.com/v1/ticker/'


# logger setup
logging.basicConfig(filename=LOG_FILENAME, level=logging.INFO,
                    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger("app")

# load config file
config = {}
with open(CONFIG_FILENAME, 'r') as f:
    try:
        config = json.load(f)
        logger.info('Config successfully loaded')
    except Exception as e:
        logger.error('Error loading config file: {}'.format(e))

def main():
    url = '{}?convert=CAD&limit={}'.format(BASE_API_URL, config['coin_limit'])
    logger.info('Using API url: {}'.format(url))
    allowed_currencies = config['currencies']

    while True:
        # get current database currency list
        coins = get_database_coins()

        # retrieve current coin data from coin market cap api
        market_data = get_market_data(url)

        # insert missing coins into database
        for coin in market_data:
            if coin['symbol'] in allowed_currencies and coin['id'] not in coins:
                insert_coin(coin)

        # get updated currency list
        coins = get_database_coins()

        # insert market data
        db_insert_market_data(coins, market_data)

        info_msg = 'Sleeping for {} seconds'.format(config['cycle_delay'])
        logger.info(info_msg)
        time.sleep(config['cycle_delay'])


def db_insert_market_data(coins, market_data):
    info_msg = 'Inserting market data into database'
    logger.info(info_msg)

    timestamp = datetime.datetime.utcnow()

    sql = ("INSERT INTO coin_prices (`currency_id`, `rank`, `price_btc`, `price_usd`, `price_cad`, `market_cap_usd`, "
           "`market_cap_cad`, `volume_usd`, `volume_cad`, `available_supply`, `total_supply`, `max_supply`, "
           "`percent_change_hour`, `percent_change_day`, `percent_change_week`, `created_at`) "
           "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)")

    db = get_db_connection()

    cursor = db.cursor()

    for md in market_data:
        if md['id'] in coins:
            for col in md:
                if md[col] is None:
                    md[col] = 0
            try:
                cursor.execute(sql, (coins[md['id']]['id'], md['rank'], md['price_btc'], md['price_usd'],
                                     md['price_cad'], md['market_cap_usd'], md['market_cap_cad'], md['24h_volume_usd'],
                                     md['24h_volume_cad'], md['available_supply'], md['total_supply'], md['max_supply'],
                                     md['percent_change_1h'], md['percent_change_24h'], md['percent_change_7d'],
                                     timestamp))
                db.commit()
            except Exception as e:
                db.rollback()
                error_msg = 'DB insert market data error: {} - {}'.format(md['id'], e)
                logger.error(error_msg)

    db.close()


def get_database_coins():
    # get coins currently in database
    db_coin_list = get_db_currencies()

    # parse coins into dictionary
    coins = dict()
    for coin in db_coin_list:
        coins[coin[4]] = {'id': coin[0], 'name': coin[1], 'symbol': coin[2]}

    return coins


def insert_coin(coin):
    info_msg = 'Inserting coin into database: {}'.format(coin['id'])
    logger.info(info_msg)

    timestamp = datetime.datetime.utcnow()

    sql = ("INSERT INTO currencies (`name`, `symbol`, `currency_type_id`, `coin_market_cap_id`, `created_at`) "
           "VALUES (%s, %s, %s, %s, %s)")

    db = get_db_connection()

    cursor = db.cursor()

    try:
      cursor.execute(sql, (coin['name'], coin['symbol'], 1, coin['id'], timestamp))
      db.commit()
    except Exception as e:
      db.rollback()
      error_msg = 'DB coin insert error: {}'.format(e)
      logger.error(error_msg)

    db.close()


def get_db_currencies():
    sql = "SELECT * FROM currencies"

    db = get_db_connection()

    cursor = db.cursor()

    try:
      cursor.execute(sql)
      return cursor.fetchall()
    except Exception as e:
      error_msg = 'DB insert error: {}'.format(e)
      logger.error(error_msg)

    db.close()


def get_db_connection():
    db = pymysql.connect(host=config['database']['host'],
                         port=config['database']['port'],
                         user=config['database']['user'],
                         password=config['database']['password'],
                         db=config['database']['db_name'])
    return db


def get_market_data(url):
    info_msg = 'Retrieving list from CoinMarketCap API'
    logger.info(info_msg)

    try:
        return requests.get(url).json()
    except Exception as e:
        error_msg = 'Error retrieving list from CoinMarketCap: {}'.format(e)
        logger.error(error_msg)
        return None


if __name__ == '__main__':
    try:
        main()
    except Exception as e:
        logger.error('Exception: {}'.format(e))
        raise
