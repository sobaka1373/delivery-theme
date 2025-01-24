const sass = require('sass');
const fs = require('fs-extra');
const path = require('path');

// Папки для SCSS и CSS файлов
const scssDir = path.join(__dirname, 'assets', 'scss');
const cssDir = path.join(__dirname, 'assets', 'css');

// Создаем CSS папку, если она не существует
fs.ensureDirSync(cssDir);

// Читаем все файлы SCSS
const scssFiles = fs.readdirSync(scssDir).filter((file) => file.endsWith('.scss'));

scssFiles.forEach((file) => {
    const scssFilePath = path.join(scssDir, file);
    const cssFilePath = path.join(cssDir, file.replace('.scss', '.css'));

    try {
        // Компиляция SCSS в CSS
        const result = sass.renderSync({
            file: scssFilePath,
            outputStyle: 'compressed', // Минимизация CSS
        });

        // Записываем результат в CSS файл
        fs.writeFileSync(cssFilePath, result.css);
        console.log(`✔ Successfully compiled ${file} to ${cssFilePath}`);
    } catch (error) {
        // Обработка ошибки с указанием файла, где она произошла
        console.error(`❌ Error compiling "${file}": ${error.message}`);
    }
});
