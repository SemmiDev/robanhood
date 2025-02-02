clean:
    npm run clean && npm run build && artisan view:clear

ln:
    ln -s storage/app/public/ ../public_html/storage
