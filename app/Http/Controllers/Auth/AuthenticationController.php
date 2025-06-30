<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use ZipArchive;

class AuthenticationController extends Controller
{

    public function createBackup(Request $request)
    {
        // Validate if the user is authorized to perform this action
        $request->validate([
            'password' => 'required|string'
        ]);

        // Check if the provided password matches the stored password
        if ($request->password !== env('BACKUP_PASSWORD')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Backup the database
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('/' . $filename);

        // Run the mysqldump command to create the backup
        $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . $backupPath;
        $output = [];
        $returnVar = null;
        exec($command, $output, $returnVar);

        // Check if the command was successful
        if ($returnVar !== 0) {
            return response()->json(['error' => 'Backup failed'], 500);
        }

        // Return the file as a download response
        return response()->download($backupPath)->deleteFileAfterSend(true);
    }

    public function backupPublicFolder(Request $request)
    {
        // Validate request (e.g., password check if necessary)
        $request->validate([
            'password' => 'required|string'
        ]);

        // Check if the provided password matches the stored password
        if ($request->password !== env('BACKUP_PASSWORD')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Define the backup directory and zip file name
        $zipFileName = 'storage_public_backup_' . date('d_m_Y_H_i_s') . '.zip';
        $zipFilePath = storage_path('/' . $zipFileName);

        // Create a ZipArchive instance
        $zip = new ZipArchive;

        // Create a zip file
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

            // Get the storage/public directory path
            $storagePublicFolder = storage_path('app/public');

            // Recursively add all files in the storage/public folder to the zip archive
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storagePublicFolder));
            foreach ($files as $file) {
                // Skip directories (they are automatically added)
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    // Extract the relative path from the storage/public directory and add to the zip
                    $relativePath = str_replace($storagePublicFolder . '/', '', $filePath);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            // Close the zip file
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create zip file'], 500);
        }

        // Return the zip file as a download response
        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    }



    public function login()
    {
        return view('auth.login');
    }

    public function forgotPassword()
    {
        return view('auth.forgotPassword');
    }

    public function otp_index(){
        return view('auth.otp');

    }

    public function otp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('forgotPassword')
                ->with('error', 'User not found');
        }

        $otpCode = rand(100000, 999999);

        Mail::to($user->email)->send(new OtpMail($user, $otpCode));

        Otp::create([
            'user_id' => $user->id,
            'otp' => $otpCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        $otp = Otp::where('otp', $request->otp)->first();
        if (!$otp) {
            return redirect()->route('otpIndex')
                ->with('error', 'Invalid OTP');

        }

        if ($otp->expires_at < now()) {
            return redirect()->route('otpIndex')
                ->with('error', 'OTP expired');
        }

        // return view('auth.newPassword', compact('otp'));

        return redirect()->route('newPasswordIndex', ['otp' => $otp->otp, 'user_id' => $otp->user_id]);

    }

    public function newPasswordIndex(Request $request)
    {

        $otp = $request->query('otp');;
        $user_id = $request->query('user_id');;

        return view('auth.newPassword', compact('otp', 'user_id'));
    }

    public function newPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            'user_id' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        $otp = Otp::where(['otp'=> $request->otp,'user_id' => $request->user_id])->first();

        if (!$otp) {
            return redirect()->route('newPasswordIndex')
                ->with('error', 'Invalid OTP');
        }
        $user = User::find($otp->user_id);
        $user->password = bcrypt($request->password);
        $user->save();
        $otp->delete();
        return redirect()->route('login')
            ->with('success', 'Password changed successfully');
    }

    public function loginConfirm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('home');
        }
        return redirect()->route('login')
            ->with('error', 'Invalid login credentials')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }
}
