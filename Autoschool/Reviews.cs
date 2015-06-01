using System;
using System.Data;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;
using iTextSharp.text.pdf;
using iTextSharp.text;

namespace Autoschool
{
    public partial class Main
    {
        public void ExportToPdf(DataTable dt)
        {
            try
            {
                //MessageBox.Show(dt.Columns.Count.ToString());
                //var document = new PdfDocument(PdfDocumentFormat.A4);
                //var table = document.NewTable(new Font("HelveticaNeueCyr", 16), 200, dt.Columns.Count, 4);
                //table.ImportDataTable(dt);
                //table.Columns[2].SetContentFormat("{0:dd/MM/yyyy}");

                //table.HeadersRow.SetColors(Color.White, Color.Navy);
                //table.SetColors(Color.Black, Color.White, Color.Gainsboro);
                //table.SetBorders(Color.Black, 1, BorderType.CompleteGrid);

                //table.SetColumnsWidth(new[] {5, 25, 16, 20, 20, 25});

                //table.Rows[7].SetColors(Color.Black, Color.LightGreen);

                //table.SetContentAlignment(ContentAlignment.MiddleCenter);
                //table.Columns[1].SetContentAlignment(ContentAlignment.MiddleLeft);

                //while (!table.AllTablePagesCreated)
                //{
                //    var page = document.NewPage();
                //    var tablePage = table.CreateTablePage(
                //        new PdfArea(document,
                //            48,
                //            120,
                //            500,
                //            670));

                //    var pta = new PdfTextArea(new Font("HelveticaNeueCyr", 26, System.Drawing.FontStyle.Bold),
                //        Color.Red, new PdfArea(document, 0, 20, 595, 120), ContentAlignment.MiddleCenter, "Report");

                //    page.Add(tablePage);
                //    page.Add(pta);

                //    page.SaveToDocument();
                //}

                //document.SaveToFile("Report1.pdf");


                var document = new Document(PageSize.A4);
                var writer = PdfWriter.GetInstance(document, new FileStream("D://example.pdf", FileMode.Create));

                document.AddAuthor(_currentUser.Name);
                document.AddLanguage("Russian");
                document.AddTitle("Report of the autoschool\n" + _currentAutoschool);

                document.Open();

                var font5 = FontFactory.GetFont(FontFactory.HELVETICA, 5);



                var table = new PdfPTable(dt.Columns.Count);
                PdfPRow row = null;
                float[] widths = {4f, 4f, 4f, 4f};

                table.WidthPercentage = 100;
                var iCol = 0;
                var colname = "";
                var cell = new PdfPCell(new Phrase("Products")) {Colspan = dt.Columns.Count};


                foreach (DataColumn c in dt.Columns)
                {
                    table.AddCell(new Phrase(c.ColumnName, font5));
                }

                foreach (var r in from DataRow r in dt.Rows where dt.Rows.Count > 0 select r)
                {
                    for (var i = 0; i < dt.Columns.Count; ++i)
                    {
                        table.AddCell(new Phrase(r[i].ToString(), font5));
                    }
                }
                document.Add(table);
                    
                document.Close();
                Process.Start("D://example.pdf");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }

        public static DataTable DataGridToDataTable(DataGrid dg)
        {
            dg.SelectAllCells();
            dg.ClipboardCopyMode = DataGridClipboardCopyMode.IncludeHeader;
            ApplicationCommands.Copy.Execute(null, dg);
            dg.UnselectAllCells();
            var result = (string)Clipboard.GetData(DataFormats.CommaSeparatedValue);
            var lines = result.Split(new[] { "\r\n", "\n" }, StringSplitOptions.None);
            var fields = lines[0].Split(',');
            var cols = fields.GetLength(0);

            var dt = new DataTable();
            for (var i = 0; i < cols; i++)
                dt.Columns.Add(fields[i].ToUpper(), typeof(string));
            for (var i = 1; i < lines.GetLength(0) - 1; i++)
            {
                fields = lines[i].Split(',');
                var row = dt.NewRow();
                for (var f = 0; f < cols; f++)
                {
                    row[f] = fields[f];
                }
                dt.Rows.Add(row);
            }
            return dt;
        }

        public static DataTable DataViewToDataTable(DataView dv)
        {
            try
            {
                var dt = dv.Table.Clone();
                foreach (DataRowView drv in dv)
                    dt.ImportRow(drv.Row);
                return dt;
            }
            catch (Exception ex)
            {
                MessageBox.Show("DataGridToDataTable:\n" + ex.Message + "\n" + ex.StackTrace);
                return null;
            }
        }
    }
}
