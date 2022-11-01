import { HttpService } from '@nestjs/axios';
import { HttpException, Injectable, NotFoundException } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { Model, Types } from 'mongoose';
import { catchError } from 'rxjs';
import { CreateCompanyDto } from './dto/create-company.dto';
import { UpdateCompanyDto } from './dto/update-company.dto';
import { Company } from './entities/company.entity';

@Injectable()
export class CompaniesService {

  constructor(
    @InjectModel(Company.name) private readonly model: Model<Company>,
    private readonly httpService: HttpService
  ) { }

  create(createCompanyDto: CreateCompanyDto) {
    const company = new this.model(createCompanyDto)
    return company.save()
  }

  async createMany(createCompanyDto: CreateCompanyDto[]) {
    try {
      await this.model.insertMany(createCompanyDto)
    } catch (error) {
      throw new Error(error.message);

    }
    return { message: 'Companies created successfully' }
  }

  async findAll() {
    return await this.model.find();
  }

  async findOne(cnpj: string) {
    const company = await this.model.findOne({ cnpj: cnpj });
    if (!company) {
      throw new NotFoundException(`Company CNPJ ${cnpj} not found`);
    }
    return company
  }

  async update(cnpj: string, updateCompanyDto: UpdateCompanyDto) {
    const company = await this.model.findOneAndUpdate({ cnpj: cnpj }, updateCompanyDto, { new: true })
    if (!company) {
      throw new NotFoundException(`Company CNPJ ${cnpj} not found`);
    }
    return company
  }

  async remove(cnpj: string) {
    const company = await this.model.findOneAndDelete({ cnpj: cnpj })
    if (!company) {
      throw new NotFoundException(`Company CNPJ ${cnpj} not found`);
    }
    await this.deleteUsersFromCompany(cnpj);
    return { message: `Company CNPJ ${cnpj} deleted successfully` }
  }

  private async deleteUsersFromCompany(cnpj: string) {
    const request = this.httpService.delete(`${process.env.USERS_SERVICE_ENDPOINT}/company/${cnpj}`).pipe(
      catchError((error) => {
        throw new HttpException('Request cant be processed, try again later', 400);
      })
    )
  }
}
