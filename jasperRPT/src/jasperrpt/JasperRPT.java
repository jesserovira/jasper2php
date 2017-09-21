/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package jasperrpt;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.HashMap;
import java.util.Properties;
import net.sf.jasperreports.engine.JasperRunManager;
import java.sql.*;
import net.sf.jasperreports.engine.JRException;
import java.util.Calendar;
import java.util.GregorianCalendar;

/**
 *
 * @author Jesse
 */
public class JasperRPT {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        
        String libfile = args[0];
        String jasperfile = args[1];
        String local = args[2];
        String usuario = args[3];
        String fileName = args[4];
        String filenameOutput;
        
        Calendar data = new GregorianCalendar();
        
        JasperRPT jrpt = new JasperRPT();
        
        Properties prop = new Properties();
        InputStream input;
        byte bytes[];
        HashMap<String, String> params = jrpt.getParametros(args);
        
	try {

            if(fileName.equals("auto"))
                filenameOutput = local+usuario+data.getTimeInMillis()+".pdf";
            else
                filenameOutput = local+fileName+".pdf";
            
            File reportFile = new File(jasperfile);
            FileOutputStream outputFile = new FileOutputStream(filenameOutput);

            input = new FileInputStream(libfile+"config.properties");
            prop.load(input);

            String driver = prop.getProperty("jasper.db.driver");
            String url = prop.getProperty("jasper.db.url");
            String username = prop.getProperty("jasper.db.user");
            String password = prop.getProperty("jasper.db.password");

            Class.forName(driver).newInstance();
            java.sql.Connection conexao = DriverManager.getConnection(url, username, password);

            if(conexao == null)
            {
                return;
            }
            bytes = JasperRunManager.runReportToPdf(reportFile.getPath(), params, conexao);

            if(bytes != null && bytes.length > 0)
            {
                outputFile.write(bytes);
                outputFile.flush();
                outputFile.close();
                
                System.out.println("#reportname#"+filenameOutput);
                //System.out.println(bytes);
            }
            input.close();
        }
        catch(IOException | ClassNotFoundException | InstantiationException | IllegalAccessException | SQLException | JRException ex)
        {
            System.err.println("erro: "+ex.getMessage());
        }
    }
    
    private HashMap<String, String> getParametros(String[] args)
    {
        HashMap<String, String> ret = new HashMap<>();
        String[] params;
        
        for (String arg : args) {
            if (arg.contains("#")) {
                params = arg.split("#");
                ret.put(params[0], params[1]);
            }
        }
        return ret;
        
    }
    
}
