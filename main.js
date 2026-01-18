const path = require('path');
const { exec, execSync } = require('child_process');
const { app, BrowserWindow } = require('electron');
const http = require('http');

let mainWindow;
let laravelServer;

function getPhpPath() {
    try {
        let phpPath = execSync('where php', { stdio: ['pipe', 'pipe', 'ignore'] })
            .toString()
            .split(/\r?\n/)[0];

        phpPath = phpPath.replace(/\\/g, '\\\\');
        return phpPath;
    } catch (err) {
        return null;
    }
}

// Absolute paths
const laravelPath = "E:\\attendance\\laravel";
const phpPath = getPhpPath();

function startLaravel() {
    laravelServer = exec(`"${phpPath}" artisan serve --host=127.0.0.1 --port=8000`, { cwd: laravelPath });

    laravelServer.stdout.on('data', data => console.log(`Laravel: ${data}`));
    laravelServer.stderr.on('data', data => console.error(`Laravel Error: ${data}`));
}

function checkServerReady(url, callback) {
    const interval = setInterval(() => {
        http.get(url, res => {
            clearInterval(interval);
            callback();
        }).on('error', () => { });
    }, 500);
}

function createWindow() {
    mainWindow = new BrowserWindow({
        width: 1200,
        height: 800,
        webPreferences: { nodeIntegration: true, contextIsolation: true }
    });

    checkServerReady('http://127.0.0.1:8000', () => {
        mainWindow.loadURL('http://127.0.0.1:8000');
    });

    mainWindow.on('closed', () => {
        mainWindow = null;
        if (laravelServer) laravelServer.kill();
    });
}

app.on('ready', () => { startLaravel(); createWindow(); });
app.on('window-all-closed', () => { if (process.platform !== 'darwin') app.quit(); });
app.on('quit', () => { if (laravelServer) laravelServer.kill(); });