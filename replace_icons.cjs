const fs = require('fs');
const path = require('path');
const https = require('https');

const viewsDir = path.join(__dirname, 'resources', 'views');

function getFiles(dir, files_) {
    files_ = files_ || [];
    const files = fs.readdirSync(dir);
    for (const i in files) {
        const name = dir + '/' + files[i];
        if (fs.statSync(name).isDirectory()) {
            getFiles(name, files_);
        } else if (name.endsWith('.blade.php')) {
            files_.push(name);
        }
    }
    return files_;
}

const fetchSvg = (icon) => {
    return new Promise((resolve, reject) => {
        const [prefix, name] = icon.split(':');
        https.get(`https://api.iconify.design/${prefix}/${name}.svg`, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => resolve(data));
        }).on('error', reject);
    });
};

async function processFiles() {
    const files = getFiles(viewsDir);
    const iconCache = {};
    let totalReplaced = 0;

    for (const file of files) {
        let content = fs.readFileSync(file, 'utf8');
        const regex = /<iconify-icon icon="([^"]+)"(?: class="([^"]*)")?><\/iconify-icon>/g;
        
        let match;
        const matches = [];
        while ((match = regex.exec(content)) !== null) {
            matches.push(match);
        }

        if (matches.length > 0) {
            console.log(`Processing ${file} - ${matches.length} icons found.`);
            let newContent = content;
            for (const m of matches) {
                const fullTag = m[0];
                const iconName = m[1];
                const classes = m[2] || '';

                if (!iconCache[iconName]) {
                    try {
                        let svg = await fetchSvg(iconName);
                        // Add class to SVG
                        if (classes) {
                            svg = svg.replace('<svg ', `<svg class="${classes}" `);
                        } else {
                            svg = svg.replace('<svg ', `<svg class="inline align-middle w-5 h-5" `); // default
                        }
                        
                        // Iconify SVGs usually have width="1em" height="1em". We can keep that or add w-5 h-5.
                        // We will just let classes handle it, or default to current size.
                        iconCache[iconName] = svg;
                    } catch (e) {
                        console.error(`Failed to fetch ${iconName}:`, e);
                        continue;
                    }
                }

                let finalSvg = iconCache[iconName];
                // if classes differ for same icon in different places, we should inject class per match, not cache the class.
                // Re-read SVG from cache, remove its class and apply new one
                let cleanSvg = iconCache[iconName];
                if(cleanSvg) {
                     cleanSvg = cleanSvg.replace(/ class="[^"]*"/, '');
                     if (classes) {
                         cleanSvg = cleanSvg.replace('<svg ', `<svg class="${classes} w-[1em] h-[1em]" `);
                     } else {
                         cleanSvg = cleanSvg.replace('<svg ', `<svg class="inline align-middle w-[1em] h-[1em]" `);
                     }
                     newContent = newContent.replace(fullTag, cleanSvg);
                     totalReplaced++;
                }
            }
            fs.writeFileSync(file, newContent, 'utf8');
        }
    }
    console.log(`Total icons replaced: ${totalReplaced}`);
}

processFiles().catch(console.error);
