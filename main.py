import telebot
from telebot import types
from flask import Flask
from threading import Thread

# --- 1. BOT SETUP ---
# Aapka provided token
API_TOKEN = '8360406945:AAENBbMVJ6dLqfXEB5-74SzSmCuoATJEfgA'
bot = telebot.TeleBot(API_TOKEN)

# --- 2. WEB SERVER (For 24/7 Hosting) ---
app = Flask('')

@app.route('/')
def home():
    return "Bot is Alive!"

def run():
    app.run(host='0.0.0.0', port=8080)

def keep_alive():
    t = Thread(target=run)
    t.start()

# --- 3. BOT LOGIC ---
@bot.message_handler(commands=['start'])
def send_id(message):
    user_id = message.from_user.id
    first_name = message.from_user.first_name
    
    text = (
        f"👋 **Hello {first_name}!**\n\n"
        f"🆔 **Your Telegram ID:** `{user_id}`\n\n"
        "Ye bot aapki user ID check karne ke liye banaya gaya hai."
    )

    # Custom Buttons
    markup = types.InlineKeyboardMarkup(row_width=1)
    
    btn_wa = types.InlineKeyboardButton("📢 Join WhatsApp Channel", url="https://whatsapp.com/channel/0029Vb6NKJ5GehEHnqkoKx3d")
    btn_tg = types.InlineKeyboardButton("🚀 Join Telegram Channel", url="https://t.me/hacking_with_selfishgirl")
    btn_owner = types.InlineKeyboardButton("👨‍💻 Owner Contact", url="https://t.me/Selfishgirl_1")
    
    markup.add(btn_wa, btn_tg, btn_owner)

    bot.send_message(message.chat.id, text, reply_markup=markup, parse_mode="Markdown")

# --- 4. EXECUTION ---
if __name__ == "__main__":
    print("Bot is starting...")
    keep_alive()  # Server start karega
    bot.infinity_polling() # Bot polling start karega
