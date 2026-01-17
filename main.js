const { app, BrowserWindow, session } = require('electron');
const { spawn } = require('child_process')
const path = require('path')

function createWindow() {
    session.defaultSession.clearCache().then(() => {
        console.log('Cache cleared!');
    });

    const win = new BrowserWindow({
        width: 1200,
        height: 800,
        webPreferences: {
            nodeIntegration: true,
            contextIsolation: false
        }
    })

    win.loadURL('http://127.0.0.1:8000')
}

app.whenReady().then(() => {
    const artisanPath = path.join(__dirname, 'artisan')
    const laravelServer = spawn('php', [artisanPath, 'serve', '--host=127.0.0.1', '--port=8000'], {
        cwd: __dirname,
        detached: false,
        stdio: 'ignore'
    })

    laravelServer.unref()
    createWindow()
})
